<?php

	startSession();
	$GLOBALS['homeUrl'] = "http://localhost:8888/home.html";
	
	$GLOBALS['spotifyCredsPath'] = "spotifyCreds.json";
	$secret = getSecret();
	$clientId = getClientID();
	if(isset($_GET['code']))
	{
		//echo "no refresh token";
		$fields = array(
			'grant_type' => urlencode("authorization_code"),
			'code' => urlencode($_GET['code']),
			'redirect_uri' => urlencode("http://localhost:8888/getRefreshToken.php")
		);
		
		$returnFields = array('access_token', 'refresh_token', 'token_type', 'scope', 'expires_in');
	}
	else
	{
		//echo "auth Expired";
		$fields = array(
			'grant_type' => urlencode("refresh_token"),
			'refresh_token' => urlencode($_SESSION['refresh_token'])
		);
		
		$returnFields = array('access_token', 'token_type', 'scope', 'expires_in');
	}		
	

	$url = 'https://accounts.spotify.com/api/token';

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
	
	foreach($returnFields as $rf)
	{
		if(isset($json[$rf]))
		{
			$string = $json[$rf];
			$_SESSION[$rf] = $string;
		}
	}
	
	$timeInSeconds = $json['expires_in'];
	$now = time();
	$now = $now + $timeInSeconds;
	$_SESSION['expiration_time'] = $now;
	
	if(!isset($_GET['noReturnHome'])){
		returnHome();
	}
		
	//echo $result;
	
	function returnHome()
	{
		$homeUrl = $GLOBALS['homeUrl'];
		header( "Location: $homeUrl" );
		exit();
	}
		
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

function hasRefreshToken()
{
	if($_SESSION['refresh_token'] == null)
		return false;
	return true;
}

function authExpired()
{
	$et = $_SESSION['expiration_time'];
	
	if($et != null)
	{
		$now = time();
		if($et < $now)
		{
			return true;
		}
	}
	return false;
}

	
?>