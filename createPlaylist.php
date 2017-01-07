<?php
	session_start();
	include 'getRefreshToken.php?noReturnHome=true';
	//echo $_GET['playlistName'];
	//return;
	$url = 'https://api.spotify.com/v1/users/'.$_SESSION['id'].'/playlists';
	//$returnFields = array(display_name, href, id);
	//echo"hi";
	if(isset($_GET['public']))
		$public = $_GET['public'];
	else
		$public  = 'true';
	//echo $_GET['name'];
	$fields = array(
		'name' => $_GET['name']
	);
	
	foreach($fields as $key=>$value)
	{
		$fields_string .= $key.'='.$value.'&';
	}
	rtrim($fields_string, '&');
	
	$data_string =  json_encode($fields);
	$header_string = $_SESSION['access_token'];
	$headers = array('Authorization: Bearer '.$header_string.' Content-Type: application/json');
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
	
	//echo $result;
	$json = json_decode($result, true);
	$playlistId = $json['id'];
	
	
	//echo 'Id = '.$playlistId.'<br>';
	$spotifyIds = json_decode($_GET['spotifyIds']);
	$uris = array();
	foreach($spotifyIds as $id)
	{
		array_push($uris, 'spotify:track:'.$id);
	}
	$data_string = json_encode($uris);
	
	$url = 'https://api.spotify.com/v1/users/'.$_SESSION['id'].'/playlists/'.$playlistId.'/tracks';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($result, true);
	echo $result;
	//echo $json['error']['message'];
	//$returnVal = array();
	/*foreach ($json['items'] as $key=>$value)
	{
		$name = $value['name'];
		$id = $value['id'];
		$node = array('name' => $name, 'id' => $id);
		array_push($returnVal, $node);
	}
	
	echo json_encode($returnVal);
	*/
?>