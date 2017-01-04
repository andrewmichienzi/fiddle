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
			$userInfo['id'] = $_SESSION['id'];
			$userInfo['href'] = $_SESSION['href'];
			$userInfo['display_name'] = $_SESSION['display_name'];
			
			if($userInfo['id'] == null)
				$userInfo['noId'] = true;
			else
				$userInfo['noId'] = false;
			
	} else {
			$userInfo['hasRefreshToken'] = 'false';
	}
	
	echo json_encode($userInfo);
?>
