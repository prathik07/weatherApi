<?php
session_start();
?>
<!DOCTYPE HTML>


<!---Updated version of 19th--->
<html lang="en">
	<head> <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>Energy Generation Forecast</title>
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> 
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
			<link rel="stylesheet" href="topNavStyle.css">	
			<link rel="stylesheet" href="checkbox.css">	<br>
<div class="container">
			<div class="col-md-3">
				&emsp;<img src="Avi-Solar-Logo-waterMark.png" width="100px" height="100px"/>
				</div>
				<div class="col-md-8">
				<h2>&emsp; AVI SOLAR ENERGY PVT LTD <h5>&emsp;&emsp;&emsp;INSPIRED BY NATURE POWERED BY SUN</h5></h2>
				</div>
			</div>
			<div class="container">
				<div class="jumbotron" style="background-color:#fbc02d">
			<h2> &emsp; Energy Generation Forecast </h2>			
				</div>
			</div>			
    </head>
	<?php
	$self = $_SERVER['PHP_SELF'];
	?>
<body>
<?php
echo '<div class="container">';
$db = mysqli_connect("localhost","root","","weatherdb");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}else{// echo "Connected successfully";
}
if(isset($_POST['submit'])){
	$check=0;
	echo'<form style="width:600px">
	<div class="row" align="Center">
	
	</div>
				<div class="row" align="Center">
						<a href ="display.php"> <input type="button" class="btn btn-success" value="Generate CSV File" class="form-control" ></a>
						<a href ="readTxtFile.php"> <input type="button" class="btn btn-success" value="Read Prediction File" class="form-control" ></a>
				</div>
		</form>';
		$plant_name = array('','','');
	if(IsChecked('plant','Midgil')){
		$plant_name[0]='MIDGIL';
    	readURL(16.74, 78.36, 3, $plant_name[0],4.6);
		$check=1;
		$_SESSION['plant3'] = 'Midgil';
    }
	if(IsChecked('plant','Rallis')){
		$plant_name [1]='Rallis';
        readURL(21.75, 72.57, 1, $plant_name[1],4.27);
		$check=1;
		$_SESSION['plant1'] = 'Rallis';
    }
	if(IsChecked('plant','Kiran_Energy')){
		$plant_name[2]='Kiran_Energy';
		readURL(18.377, 74.465, 2, $plant_name[2],4.29);
		$check=1;
		$_SESSION['plant2'] = 'Kiran_Energy';
    }
	if($check == 0){
		$message = "Please Select any of the Plant";
	echo "<script type='text/javascript'>alert('$message');</script>";
	exit();
	}
	$db->close();
	exit();
}
function IsChecked($chkname,$value){
	if(!empty($_POST[$chkname])){
		foreach($_POST[$chkname] as $chkval){
		if($chkval == $value){
			return true;
		}
	}
}
return false;
}
function readURL($lati, $logi, $plantID, $plantName, $in){
$url = 'https://api.darksky.net/forecast/1d9536895ae231f77525511f7a28238d/'.$lati.','.$logi.'?units=auto&exclude=currently,hourly,minutely';
//https://api.darksky.net/forecast/1d9536895ae231f77525511f7a28238d/16.74,78.36?units=auto&exclude=currently,hourly,minutely";
// $url = 'http://api.worldweatheronline.com/premium/v1/weather.ashx?key=6a04238bc5ba43f883265101171207&q='.$lati.','.$logi.'&num_of_days=1&tp=3&format=json';
$content = file_get_contents($url);
$json = json_decode($content, true);
	echo '<div class="container">
			<div class="row" >
				<table class="table" >
					<tr>&emsp;&emsp;
						<div class="row">&emsp;&emsp;
							<th><label> PlantID</label></th>
							<th><label> Plant Name</label></th>
							<th><label> Date</label></th>
							<th><label> Temperature (deg C)</label></th>
							<th><label> Wind Speed (m/s)</label></th>
							<th><label> Insolation (kWh/sq.m)</label></th>
						</div>
					</tr>';
	$mulfact = 1;
	$loop = 0;
	$basetemp = 1;
    foreach($json['daily']['data'] as $item) { 
		$timestamp = $item['time'];
		$datetimeFormat = 'Y-m-d';
		$date = new \DateTime();
		$date->setTimestamp($timestamp);
		$dateValue = $date->format($datetimeFormat);
		$today = date("Y-m-d");
		$tomw = date('Y-m-d',strtotime("+1 days"));
		$wind = $item['windSpeed'];
		$tempe = $item['temperatureMax'];
	if(($dateValue > $today)){
			$futuredate = $dateValue;
			$windSpeed = $item['windSpeed'];
			$temp = $item['temperatureMax'];
			
			$insertPlantInfo = "INSERT INTO weatherinfo (Plant_ID,Date,Temperature,Wind_Speed)VALUES('$plantID','$futuredate','$temp','$windSpeed')";
			$result = mysqli_query($GLOBALS['db'],$insertPlantInfo);
			$loop++;
			if ($loop == 1) {
				$basetemp = $temp;
				$mulfact = $in / $basetemp;
				
			}
		    
			$in = $mulfact * $temp;
			$dis = number_format($in, 2, '.', '');			
	echo '&emsp;&emsp;<tr>
						<div class="row">
							<td>'.$plantID.'</td>
							<td>'.$plantName.'</td>
							<td>'.$futuredate.'</td>
							<td>'.$temp.'</td>
							<td>'.$windSpeed.'</td>
							<td>'.$dis.'</td>
						</div>
					</tr>';
					
					
			//$t++;
		}
	}
   echo '		</table>
			</div>
		</div>';
	}
	echo '</div>';
?>				
<div class="container">	
	<form action="#" method="post" style="width:600px">
		<div class="row" >
			<table class="table" class="col-md-5"cellpadding="30">
			<tr>
			<div class="row">
				<th>&ensp; &ensp;<label><h4>Select The Plant</h4></label> &emsp;
					<td>
					<div class="col-md-8">
					<div class="funkyradio">
						<div class="funkyradio-success">
							<input type="checkbox" name="plant[]" id="Rallis" value="Rallis"/>
							<label for="Rallis">Rallis (4.4 MW )</label>
						</div>
												
						<div class="funkyradio-success">
							<input type="checkbox" name="plant[]" id="Kiran_Energy" value="Kiran_Energy"/>
							<label for="Kiran_Energy">Kiran_Energy (4.6 MW )</label>
						</div>
						
						<div class="funkyradio-success">
							<input type="checkbox" name="plant[]" id="Midgil" value="Midgil"/>
							<label for="Midgil">Midgil (10 MW )</label>
						</div>						
					</div>
					</div>
					</td>
				</th>
			</div>
			</tr>
			<tr>
				<div class="row" >
				<th>&emsp;
					<td></br>
						<input type="submit" value="Submit" name="submit" class="form-control">
					</td>
				</th>
				</div>
			</tr>
		</table>
		</div>
	</form>
	</div>	
</body>
	
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>  
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>	
</html>

	