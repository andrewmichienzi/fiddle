<?php
	session_start();
	$_GET['noReturnHome']=true;
	include 'getRefreshToken.php';
	$GLOBALS['outputJson'] = "outputJson.json";
	$GLOBALS['findProgrammer'] = "Linda";
	$date = "2016-12-06";
	//echo "hi";
	$json = getPlaylist($date);
	$data = parseSets($json[0]);
	//echo $data['number_of_tracks'] . '<br><br>';
	$spotifyJson = getSpotifyInformation($data);
	echo json_encode($spotifyJson);
	//Next, add song to playlist. Need playlist id 
	
	function getSpotifyInformation($data)
	{
		//echo $data[2];
		//echo "HI<br>";
		//$numberOfTracks = $data['number_of_tracks'];
		//echo getTrackInfo('The First Ten Minutes Of Cocksucker Blues');
		$returnVal = array();
		//echo json_encode($data, true);
		//echo '<br>HI';
		$i = 0;
		foreach($data['tracks'] as $track)
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
			$returnVal[$i] = $r;
			$i++;
		}
		return $returnVal;
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
		$tracks = $json['tracks'];
		if($tracks['total'] != 0)
		{
			$songInfo = $tracks['items'][0];
			$returnArray = array(
				'id' => $songInfo['id'],
				'artist' => $songInfo['artists'][0]['name'],
				'album' => $songInfo['album']['name'],
				'previewUrl' => $songInfo['preview_url']
				);
			return $returnArray;
		}
		if(isset($json['error']))
		{
			//do something
		}
		if(isset($tracks['items']==0)
		{
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
		$url = "https://grcmc.org/wyce/playlists/date/".$data.".json";
		$xml = file_get_contents($url);
		//$file = "outputPhp.txt";
		if($xml)
		{
			$json = json_decode($xml, true);
			$playlist = $json['data']['playlist'];
			
			return $playlist;
		}
	}
/*
	function parsePlaylistData($json)
	{
		$programmer = "";
		$date = "";
		$returnVal = array();
		foreach($json as $key=>$val)
		{
			if(strcmp($key, "programmer")==0)
				$programmer = $val;
			if(strcmp($key, "date")==0)
				$date = $val;
			if(strcmp($key, "time")==0)
				$time = $val;
			if((strcmp($key, "sets")==0) && (strcmp($programmer, $GLOBALS['findProgrammer'])==0)){
				//$tempArray=parseSets($programmer, $date, $time, $val);
				echo $tempArray['number_of_tracks'];
				array_push($returnVal, $tempArray);
				
			}
		}	
		$string = json_encode($returnVal);
		echo $string;
		exit;
		return $returnVal;
	}
*/
	
?>