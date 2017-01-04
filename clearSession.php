<?php
	session_start();
	$sessionVariables = array(
		'refresh_token', 
		'access_token', 
		'token_type', 
		'scope', 
		'expires_in', 
		'expiration_time',
		'hasRefreshToken',
		'id',
		'href',
		'display_name'
	);
	
	foreach($sessionVariables as $sv)
	{
		if (isset($_SESSION[$sv]))
		{
			$_SESSION[$sv] = array();
		}
	}
?>