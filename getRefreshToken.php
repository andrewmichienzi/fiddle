<?php

	startSession();
	$homeUrl = "http://localhost:8888/home.html";
	
	$GLOBALS['spotifyCredsPath'] = "spotifyCreds.json";
	$secret = getSecret();
	$clientId = getClientID();

	
	$url = 'https://accounts.spotify.com/api/token';
	$fields = array(
		'grant_type' => urlencode("authorization_code"),
		'code' => urlencode($_GET['code']),
		'redirect_uri' => urlencode("http://localhost:8888/getRefreshToken.php")
	);

	foreach($fields as $key=>$value)
	{
		$fields_string .= $key.'='.$value.'&';
	}
	rtrim($fields_string, '&');
	
	$header_string = base64_encode($clientId . ':'. $secret);
	$headers = array('Authorization: Basic '.$header_string);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($result, true);
	$timeInSeconds = $json['expires_in'];
	$now = time();
	$_SESSION['expiration_time'] = $now + $timeInSeconds;
	
	
	//Add to session variables
	$string = $json['refresh_token'];
	$_SESSION["refresh_token"] = $string;
	$string = $json['access_token'];
	$_SESSION['access_token'] = $string;
	$string = $json['token_type'];
	$_SESSION['token_type'] = $string;
	$string = $json['scope'];
	$_SESSION['scope'] = $string;
	
	$_SESSION['expires_in'] = $timeInSeconds;
	$_SESSION['hasRefreshToken'] = true;
	
	while (ob_get_status()) 
	{
		ob_end_clean();
	}

	header( "Location: $homeUrl" );
	exit();
	
	
	
	
	function getSecret(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$secret = $credsJson['secret'];
	return $secret;
	}

function getClientID(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$clientId = $credsJson['clientId'];
	return $clientId;
}

function startSession()
{
	session_start();
	$sessionVariables = array(
		'refresh_token', 
		'access_token', 
		'token_type', 
		'scope', 
		'expires_in', 
		'expiration_time',
		'hasRefreshToken'
		);
	if( ! isset($_SESSION['refresh_token']))
		$_SESSION['refresh_token'] = array();
	foreach($sessionVariables as $sv)
	{
		if ( ! isset($_SESSION[$sv]))
		{
			$_SESSION[$sv] = array();
		}
	}
}

	
?>