<?php

function checkSessionInformation() {
	session_start();
	// Check if user information is set
	if(!(isset($_SESSION['refresh_token']) 
		&& isset($_SESSION['access_token'])))
	{
		//getRefreshToken()
		session_regenerate_id(true);
		$redirect_uri = "http://localhost:8888/wycePlaylists.html";
		$state = "koolaid";
		$clientId = getClientID();
		header("Location: https://accounts.spotify.com/authorize/?client_id=".$clientId."&response_type=code&redirect_uri=".$redirect_uri+"&state=".$state;
		//header("Location: ../Home/login.html");
		exit();
	// Check if the group id is set
	} else{
		session_regenerate_id(true);
		header("Location: ./home.html");
		exit();
	} 
}

function getClientID(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$clientId = $credsJson['clientId'];
	echo $clientId;
}
?>

/*
	if refresh token and access token are not set
		getRefreshToken();
	else
		if expired
			get new access token
			
	else
		front page
*/