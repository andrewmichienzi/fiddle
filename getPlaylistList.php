<?php
	session_start();
	include 'getRefreshToken.php?noReturnHome=true';
	$url = 'https://api.spotify.com/v1/users/'.$_SESSION['id'].'/playlists';
	$returnFields = array(display_name, href, id);
	
	$test = $_SESSION['access_token'];
	
	$header_string = $_SESSION['access_token'];
	$headers = array('Authorization: Bearer '.$header_string);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
	
	//echo $result;
		
	$json = json_decode($result, true);
	$returnVal = array();
	foreach ($json['items'] as $key=>$value)
	{
		//echo $value
		$name = $value['name'];
		$id = $value['id'];
		
		$imageUrl = $value['images'][0]['url'];
		//echo $imageUrl;
		
		$node = array('name' => $name, 'id' => $id, 'imageUrl' => $imageUrl);
		array_push($returnVal, $node);
	}
	
	echo json_encode($returnVal);
	
?>