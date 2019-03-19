<?php
	session_start();
	//echo 'hi';
	$_GET['noReturnHome']=true;
	include 'getRefreshToken.php';
	//$_GET['date'] = "2016-12-05";
	getListOfSongs();

	// echo 'here';
	
	function getListOfSongs()
	{
	$json = getPlaylist($_GET['date']);
	$returnArray = array();
	
	if(isset($_GET['programmer']))
	{
		foreach($json as $value)
		{
			if($value['programmer'] === $_GET['programmer'])
			{
				$data = parseSets($value);
				$data['programmer'] = $_GET['programmer'];
				$returnArray[0] = $data;
			}
		}
	}
	else
	{
		$i = 0;
		foreach($json as $value)
		{
			$programmerArray = parseSets($value);
			$programmerArray['programmer'] = $value['programmer'];
			$returnArray[$i] = $programmerArray;
			$i = $i + 1;
			//echo json_encode($returnArray);
			//exit;
		}
	}
	$spotifyJson = getSpotifyInformation($returnArray);
	echo json_encode($spotifyJson);
	}
	
	function getSpotifyInformation($data)
	{
		$returnVal = array();
		$programmerI = 0;
		$i = 0;
		foreach($data as $programmer)
		{
			$trackI = 0;
			foreach($programmer['tracks'] as $track)
			{	
				$r = getTrackInfo($track['track_title'], $track['artist'], $track['album']);
				if($r == "null")
				{
					$r = getTrackInfo($track['track_title'],  $track['artist']);
					if($r == "null")
					{
						$r = getTrackInfo($track['track_title']);
					}
				}
				$data[$programmerI]['tracks'][$trackI]['spotifyInfo'] = $r;
				$returnVal[$i] = $r;
				$returnVal['programmer'] = $programmer['programmer'];
				$i = $i + 1;
				$trackI = $trackI + 1;
			}
			$programmerI = $programmerI + 1;
		}
		return $data;
	}
	
	function parseSets($data)
	{	
		$json = array(
				"programmer" => $data['programmer'],
				"date" => $data['date'],
				"time" => $data['time']
		);
		$trackList = array();
		$numOfTracks = 0;
		$sets =  $data['sets'];
		foreach($sets as $setNum=>$val)
		{
			if(is_array($val))
			{
				//sets
				$tracks = $val['tracks'];		
				foreach($tracks as $key=>$track)
				{
					//track
					array_push($trackList, $track);	
					$numOfTracks+=1;
				}
			}
		}
		$json['number_of_tracks'] = $numOfTracks;
		$json['tracks'] =$trackList;
		//echo json_encode($json);
		return $json;
	}
	
	function appendToUrl($appendToUrl)
	{
		$returnVal = '?q=';
		$i = 0;
		foreach($appendToUrl as $x)
		{
			if($i === 0)
			{
				$returnVal .= $x;
			}
			else if($x != null)
			{
				$returnVal .= '+'.$x;
			}
			$i += 1;
		}
		$string = str_replace(" ", "+", $returnVal);
		
		$string .= '&type=track';

		return $string;
	}
	
	function getSongInfo($json)
	{
		// echo $json['tracks']['href'];
		// exit;
		$tracks = $json['tracks'];
		if($tracks['total'] != 0)
		{
			$songInfo = $tracks['items'][0];
			//echo json_encode($songInfo);
			//exit;
			$image = $songInfo['album']['images'][2];
			if(null === $image)
			{
				$image = $songInfo['album']['images'][1];
				if(null === $image)
					$image = $songInfo['album']['images'][0];
			}
				
			$returnArray = array(
				'id' => $songInfo['id'],
				'artist' => $songInfo['artists'][0]['name'],
				'album' => $songInfo['album']['name'],
				'previewUrl' => $songInfo['preview_url'],
				'image' => $image
				);
			return $returnArray;
		}
		/*if(null !== $json['error'])
		{
			echo '<br><br> ********error********** <br><br>';
			//do something
		*/
		if($tracks['items']==0)
		{
			echo '<br><br> ********no items********** <br><br>';
			//do something
		}
		return $json;
	}
	
	function getTrackInfo($track_title, $artist='', $album='')
	{
		$appendToUrl =  array($track_title, $artist, $album);
		
		$url = 'https://api.spotify.com/v1/search';
		$url .= appendToUrl($appendToUrl);
			
		$header_string = $_SESSION['access_token'];
		$headers = array('Authorization: Bearer '.$header_string.' Accept: application/json');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		curl_close($ch);
		
		$json = json_decode($result, true);
		
		return getSongInfo($json);
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
?>