<?php

if(IsChecked('plant','Midgil')){
    $plantName="MIDGIL";
    echo "Plant Name: Midgil <br> PLant ID: 1 <br>";
    readURL(16.74, 78.36, 1, $plantName);
}
if(IsChecked('plant','Rallis')){
    //$plantID = 2;
    $plantName= Rallis;
    echo "Plant Name: Rallis <br> PLant ID: 2 <br>";
    readURL(21.75, 72.57, 2, $plantName);
}
if(IsChecked('plant','Kiran_Energy')){
   // $plantID = 3;
    $plantName=Kiran_Energy;
    echo "Plant Name: Kiran Energy <br> PLant ID: 3 <br>";
    readURL(18.377, 74.465, 3, $plantName);
}
	function IsChecked($chkname,$value)	{
		if(!empty($_POST[$chkname])){
			foreach($_POST[$chkname] as $chkval){
				if($chkval == $value){
					return true;
				}
			}
		}
		return false;
	}
	function readURL($lati, $logi,$plantID,$plantName){
		$url = 'https://api.darksky.net/forecast/1d9536895ae231f77525511f7a28238d/'.$lati.','.$logi;
		// $url = 'http://api.worldweatheronline.com/premium/v1/weather.ashx?key=6a04238bc5ba43f883265101171207&q='.$lati.','.$logi.'&num_of_days=1&tp=3&format=json';
		$content = file_get_contents($url);
		$json = json_decode($content, true);
		foreach($json['hourly']['data'] as $item) { 
			echo "<br/>"; 
			$timestamp = $item['time'];
			$datetimeFormat = 'Y-m-d H:i:s';
			$date = new \DateTime();
			$date->setTimestamp($timestamp);
			echo "Plant ID: ".$plantID."<br>";
			echo "Plant Name: ".$plantName."<br>";
			echo "Date: ".$date->format($datetimeFormat)."<br/>";
			echo "Temperature: ".$item['temperature']."<br/>";
			$temperature = $item['temperature'];
			echo "Wind Speed: ".$item['windSpeed']."<br/>";
			$windSpeed = $item['windSpeed'];
			echo $_POST['plantID'];
			echo $_POST[$temperature];
		}
	}
?>