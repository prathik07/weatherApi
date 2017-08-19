<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="en">
	<head> <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>Energy Generation Forecast </title>
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> 
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
			<h2> &emsp;Energy Generation Forecast </h2>			
				</div>
			</div>
			
<?php
echo '<div class="container">';
	$self = $_SERVER['PHP_SELF'];
	$weatherfilename = "weather_data_".date('d-m-Y').".csv";
	$csvfile = fopen($weatherfilename, "w") or die("Unable to open file!");
	
	$db = mysqli_connect("localhost","root","","weatherDB");
	
	$display = "SELECT T2.Date,T2.Plant_ID,T1.Insolation,T2.Temperature,T2.Wind_Speed,T1.Latitude,T1.Longitude,T3.conn_load,T3.age,T3.dc_cap,T3.mod_make,T3.num_mod,T3.num_strings,T3.num_scbs,T3.inv_make,T3.num_invs,T3.plant_id,T3.tit_type FROM plantinfo T1,weatherinfo T2, plantdetails T3
	             WHERE T1.plant_id = T2.plant_id and T2.plant_id = T3.plant_id";
	$result = mysqli_query($db,$display);
	//echo $_SESSION["plant"];
	if (mysqli_num_rows($result)>0) {
		// echo '<div class="col-md-8">
				// <div class="row" >
					// <table class="table" >&emsp;&emsp;
						// <tr>
							// <div class="row">&emsp;&emsp;
								 // <th> <label> PlantID </label> </th>
								// <th> <label> Date </label> </th>
								// <th> <label> Temperature </label> </th>
								// <th> <label> Wind Speed </label> </th>
								// <th> <label> Insolation </label> </th>
							// </div>
						// </tr>';						
			while($row = mysqli_fetch_assoc($result)) {
			fputcsv($csvfile,$row);
			// echo '&emsp;&emsp;<tr>
							// <div class="row">
								// <td>'.$row['Plant_ID'].'</td>
								// <td>'.$row['Date'].'</td>
								// <td>'.$row['Temperature'].'</td>
								// <td>'.$row['Wind_Speed'].'</td>							
							// </div>
						// </tr>';
		 }
		// echo "		</table>
				// </div>
			// </div>";
	} else {
		echo "<h3>Result: Empty Table</h3>";
	}
	fclose($csvfile);
	
	$message = "CSV file Generated";
	echo "<script type='text/javascript'>alert('$message');</script>";
	
	$delete ="DELETE FROM prediction";
	$deleteResult = mysqli_query($db,$delete);
	
	$db->close();
	//echo '</div>';
?>
</head>
<body>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>  
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</html>