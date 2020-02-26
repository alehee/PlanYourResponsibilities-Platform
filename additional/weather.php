<?php
require_once("func.php");

$conn = connect();

function zeroDayWeather(){
	$weatherInfo = "";

	$xml = simplexml_load_file("http://api.openweathermap.org/data/2.5/forecast?q=Gliwice&appid=1ae8dce6887ad8d7292a9d18180f139b&mode=xml");
	$date = date("Y-m-d");

	// ZDJĘCIE
	$weatherInfo = $weatherInfo.'<img src="http://openweathermap.org/img/wn/'.$xml->forecast[0]->time[0]->symbol['var'].'@2x.png" />';
	// TEMPERATURA
	$temperature = $xml->forecast[0]->time[0]->temperature['value'] - 273;
	$temperature = (int)$temperature;
	$weatherInfo = $weatherInfo.'<div style="clear:both; margin-top:-25px; margin-bottom:5px; font-size:150%;"><b>'.$temperature.'°C</b></div>';
	// WIATR
	$wind = $xml->forecast[0]->time[0]->windSpeed['mps'] * 1.609344;
	$wind = round($wind, 2);
	$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$wind.' Km/h</div>';
	// CIŚNIENIE
	$pressure = $xml->forecast[0]->time[0]->pressure['value'];
	$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$pressure.' hPa</div>';

	return $weatherInfo;
}

function firstDayWeather(){
	$weatherInfo = "";

	$xml = simplexml_load_file("http://api.openweathermap.org/data/2.5/forecast?q=Gliwice&appid=1ae8dce6887ad8d7292a9d18180f139b&mode=xml");
	$today = date("Y-m-d");
	$date = date("Y-m-d", strtotime($today .' +1 day'))."T12:00:00";

	for($i=0; $i<20; $i++){
		if($xml->forecast[0]->time[$i]['from'] == $date){
			// ZDJĘCIE
			$weatherInfo = $weatherInfo.'<img src="http://openweathermap.org/img/wn/'.$xml->forecast[0]->time[$i]->symbol['var'].'@2x.png" />';
			// TEMPERATURA
			$temperature = $xml->forecast[0]->time[$i]->temperature['value'] - 273;
			$temperature = (int)$temperature;
			$weatherInfo = $weatherInfo.'<div style="clear:both; margin-top:-25px; margin-bottom:5px; font-size:150%;"><b>'.$temperature.'°C</b></div>';
			// WIATR
			$wind = $xml->forecast[0]->time[$i]->windSpeed['mps'] * 1.609344;
			$wind = round($wind, 2);
			$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$wind.' Km/h</div>';
			// CIŚNIENIE
			$pressure = $xml->forecast[0]->time[$i]->pressure['value'];
			$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$pressure.' hPa</div>';
		}
	}

	return $weatherInfo;
}

function secondDayWeather(){
	$weatherInfo = "";

	$xml = simplexml_load_file("http://api.openweathermap.org/data/2.5/forecast?q=Gliwice&appid=1ae8dce6887ad8d7292a9d18180f139b&mode=xml");
	$today = date("Y-m-d");
	$date = date("Y-m-d", strtotime($today .' +2 day'))."T12:00:00";

	for($i=0; $i<20; $i++){
		if($xml->forecast[0]->time[$i]['from'] == $date){
			// ZDJĘCIE
			$weatherInfo = $weatherInfo.'<img src="http://openweathermap.org/img/wn/'.$xml->forecast[0]->time[$i]->symbol['var'].'@2x.png" />';
			// TEMPERATURA
			$temperature = $xml->forecast[0]->time[$i]->temperature['value'] - 273;
			$temperature = (int)$temperature;
			$weatherInfo = $weatherInfo.'<div style="clear:both; margin-top:-25px; margin-bottom:5px; font-size:150%;"><b>'.$temperature.'°C</b></div>';
			// WIATR
			$wind = $xml->forecast[0]->time[$i]->windSpeed['mps'] * 1.609344;
			$wind = round($wind, 2);
			$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$wind.' Km/h</div>';
			// CIŚNIENIE
			$pressure = $xml->forecast[0]->time[$i]->pressure['value'];
			$weatherInfo = $weatherInfo.'<div style="clear:both; font-size:80%;">'.$pressure.' hPa</div>';
		}
	}

	return $weatherInfo;
}

$conn -> close();

?>