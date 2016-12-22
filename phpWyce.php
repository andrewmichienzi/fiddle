<?php


$GLOBALS['spotifyCredsPath'] = "spotifyCreds.json";
$functionId = $_GET['functionId'];

if($functionId == 2)
{
	getClientID();
} 

if($functionId == 3)
{
	getSecret();
}

if($functionId == 4)
{
	requestToken();
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

function getSecret(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$secret = $credsJson['secret'];
	echo $secret;
	
}

function getClientID(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$clientId = $credsJson['clientId'];
	echo $clientId;
}

function setSecret(){
	$creds = file_get_contents($GLOBALS['spotifyCredsPath']);
	$credsJson = json_decode($creds, true);
	$GLOBALS['secret'] = $credsJson['secret'];
}

function requestToken()
{
	$destUrl = $_GET['destUrl'];
	$grant_type = $_GET['grant_type'];
	$code = $_GET['code'];
	$redirect_uri = $_GET['redirect_uri'];
	//echo "Dest url = ".$destUrl;
	//echo "grant type = ".$grant_type;
	//echo "code = ".$code;
	//echo "redirect_uri = " . $redirect_uri;
	setSecret();
	//echo "secret = ". $GLOBALS['secret'];
	
	$data = array('grant_type' => $grant_type, 'code' => $code, 'redirect_uri' => $redirect_uri);
	$postVars = http_build_query($data);
	$ch  = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $destUrl);
	curl_setopt($ch, CURLOPT_POST,  count($data));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$GLOBALS['secret']));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));
	$result = curl_exec($ch);
	curl_close($ch);
	echo $results;
	return;
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Authorization: ".$GLOBALS['secret'],
			'method'  => 'POST',	
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($destUrl, false, $context);
	if ($result === FALSE) 
	{
		/* Handle error */ 
		echo "Yikes";
	}
		
	var_dump($result);
}
?>
















































