<?php
	session_start();
	
	$userInfo = array();
	if(isset($_SESSION['refresh_token']) 
		&& isset($_SESSION['access_token'])) {
			$userInfo['refresh_token'] = $_SESSION['refresh_token'];
			$userInfo['access_token'] = $_SESSION['access_token'];
			$userInfo['scope'] = $_SESSION['scope'];
			$userInfo['expires_in'] = $_SESSION['expires_in'];
			$userInfo['hasRefreshToken'] = 'true';
	} else {
			$userInfo['hasRefreshToken'] = 'false';
	}
	/*
	if(isset($_SESSION['groupId'])) {
		$userInfo['groupId'] = $_SESSION['groupId'];
		$userInfo['groupName'] = $_SESSION['groupName'];
		$userInfo['groupVar'] = 'true';
	} else {
		$userInfo['groupVar'] = 'false';
	}
	*/
	echo json_encode($userInfo);
	
	/*
	if refresh token and access token are not set
		getRefreshToken();
	else
		if expired
			get new access token
		
*/
?>
