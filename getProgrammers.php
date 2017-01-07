<?php
	
	//$_GET['date'] = "2016-12-05";
	getList();
	
	function getList()
	{
		$playlist = getPlaylist($_GET['date']);
		//echo json_encode($playlist);
		$programmers = getProgrammers($playlist);
		echo json_encode($programmers);
	}

	function getPlaylist($date)
	{
		$url = "https://grcmc.org/wyce/playlists/date/".$date.".json";
		$xml = file_get_contents($url);
		//$file = "outputPhp.txt";
		if($xml)
		{
			$json = json_decode($xml, true);
			$playlist = $json['data']['playlist'];
			
			return $playlist;
		}
	}		
	
	function getProgrammers($playlist)
	{
		$programmers = array();
		$i = 0;
		foreach($playlist as $value)
		{
			array_push($programmers, $value['programmer']);
		}
		return $programmers;
	}
?>