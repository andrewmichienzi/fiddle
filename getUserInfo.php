<?php
	session_start();
	$url = 'https://api.spotify.com/v1/me';
	$returnFields = array(display_name, href, id);
	
	$test = $_SESSION['access_token'];
	//echo "Access token = $test";
	
	$header_string = $_SESSION['access_token'];
	$headers = array('Authorization: Bearer '.$header_string);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
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
			$return = $_SESSION[$rf];
		}
	}
?>