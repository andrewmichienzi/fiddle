<?php


$GLOBALS['spotifyCredsPath'] = "spotifyCreds.json";
$functionId = $_GET['functionId'];
if($functionId == 2)
{
	getClientID();
} 


function getJson()
{
$xml = file_get_contents("https://grcmc.org/wyce/playlists/date/2016-12-06.json");
$file = "outputPhp.txt";
$GLOBALS['outputJson'] = "outputJson.json";
$GLOBALS['findProgrammer'] = "Linda";
if($xml)
{
	$json = json_decode($xml, true);
	foreach($json as $key=>$val) {
		if(strcmp($key,"data")==0)
		{
			foreach($val as $key2=>$val2)
			{
				if(strcmp($key2,"playlist")==0)
				{
					foreach($val2 as $list=>$val3)
					{
						parsePlaylistData($val3);
					}
				}
			}
		}
	}
}
}

function parsePlaylistData($json)
{
	$programmer = "";
	$date = "";
	foreach($json as $key=>$val)
	{
		if(strcmp($key, "programmer")==0)
			$programmer = $val;
		if(strcmp($key, "date")==0)
			$date = $val;
		if(strcmp($key, "time")==0)
			$time = $val;
		if((strcmp($key, "sets")==0) && (strcmp($programmer, $GLOBALS['findProgrammer'])==0))
			parseSets($programmer, $date, $time, $val);
	}	
}

function parseSets($programmer, $date, $time, $sets)
{	
	$json = array(
			"programmer" => $programmer,
			"date" => $date,
			"time" => $time
	);
	$trackList = array();
	$numOfTracks = 0;
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
				foreach($track as $k=>$v)
				{
					//Each attribute
					//echo "$k: $v\n";
				}
			//echo "\n\n";
			$numOfTracks+=1;
			}
			#echo "i = $i";
		}
	}
	$json['number_of_tracks'] = $numOfTracks;
	//array_push($json, "number_of_tracks" => $numOfTracks));
	$json['tracks'] =$trackList;
	//file_put_contents($GLOBALS['outputJson'], json_encode($json));
	$string="";
	$string = json_encode($json);
	echo $string;
}

function getClientID(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$clientId = $credsJson['clientId'];
	echo $clientId;
	/*$secret = $credsJson['secret'];
	$redirect_uri="https://localhost:8888";
	$url = "https://accounts.spotify.com/v1/me";
	
	$curl = curl_init("https://accounts.spotify.com/authorize/?client_id=c5e05e3d23464581955c9312234e3e7e&response_type=code&redirect_uri=http://localhost:8888/wycePlaylists.html&state=koolaid");
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec($curl);
	curl_close($curl);*/
}
?>
















































