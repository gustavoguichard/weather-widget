<?php

function getURLContents($query){
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $query);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
	// execute the cURL
	$data = curl_exec($c);
	curl_close($c);
	return $data;
}

function formatQuery($yql){
	$query = "http://query.yahooapis.com/v1/public/yql?q=";
	$query .= urlencode($yql);
	$query .= "&format=json";
	$result = json_decode(getURLContents($query));
	return $result->query->results;
}

function getFeed($woeid){
	require_once('inc/simplepie.inc');
	require_once('inc/simplepie_yahoo_weather.inc');
	 
	$feed = new SimplePie();
	$feed->set_feed_url('http://weather.yahooapis.com/forecastrss?w=' . $woeid . "&u=f");
	$feed->set_item_class('SimplePie_Item_YWeather');
	$feed->init();
	return $feed->get_item(0);
}

function getWoeid($cityname){
	$n = formatQuery("select woeid from geo.places where text='" . $cityname . "'");
	if(is_string($n->place->woeid)){
		return $n->place->woeid;
	} elseif(is_array($n->place)){
		return $n->place[0]->woeid;
	} else {
		return "";
	}
}

function getWeather(){
	$woeid = false;
	$print = "<div id='weatherdata'>";

	if($_GET["woeid"]){
		$woeid = $_GET["woeid"];
	} else if($_POST["cityname"]) {
		$woeid = getWoeid($_POST["cityname"]);
	}
	if($woeid != "" && $woeid){
		
		$weather = getFeed($woeid);
		
		if(!$weather->get_temperature()){
			$print .= "<h5>Can't find the location, please try again</h5>";
		} else {
			
			//$condition_code = $weather->get_condition_code();
			$temperature = $weather->get_temperature();
			$city = $weather->get_city();
			$region = $weather->get_region();
			$condition_image = $weather->get_condition_image();
			$forecasts = $weather->get_forecasts();
			$low = $forecasts[0]->get_low();
			$high = $forecasts[0]->get_high();
			
			$print .= "<span id='locationfound'>" . $city . ", " . $region . "</span>";
			$print .= "<span class='weekday'>" . date('D') . "</span>";
			$print .= "<img src='" . $condition_image . "' style='width:60px;height:60px' />";
			$print .= "<p><strong>" . $temperature . "º F</strong></p>";
			$print .= "<p>Max: " . $high . "</br>Min: " . $low . "</p>";
		}
	} else {
		if(is_string($woeid)){$print .= "<h5>Can't find your location, please try again</h5>";}
	}
	
	$print .= "</div>";
	
	echo $print;
}

if($_GET["woeid"]){
	getWeather();
}
	
?>