<?php
	$GLOBALS['spotifyCredsPath'] = "spotifyCreds.json";
	$secret = getSecret();
	$clientId = getClientID();

	$url = 'https://accounts.spotify.com/api/token';
	$fields = array(
		'grant_type' => urlencode("refresh_token"),
		'refresh_token' => urlencode($_POST['refresh_token'])
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
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
	echo $result;
	
	
	
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
?>