<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//Get data from the form
	if (isset($_POST['place']) && isset($_POST['type']) && isset($_POST['status'])) {
		$id = $_POST['id'];
		$place = $_POST['place'];
		$type = $_POST['type'];
		$description = $_POST['description'];
		if (isset($_POST['temperature'])) {
			$temperature = $_POST['temperature'];
		}
		if (isset($_POST['humidity'])) {
			$humidity = $_POST['humidity'];
		}
		if (isset($_POST['wind'])) {
			$wind = $_POST['wind'];
		}
		$status = $_POST['status'];
		
		//Create the missing data
			$reqPlace = "SELECT * FROM place WHERE name='$place'";
			$ansPlace = $conn->query($reqPlace);

		//Modules are added to the database on the same day they are installed
			$installation = date("Y-m-d");
			if ($ansPlace->num_rows > 0) {
	            while($row = $ansPlace->fetch_assoc()) {                
	                $code = $row["code"];
	                $nbr = $row["module"] + 1;
	                $name = $code. "" .$nbr;
	            }
	        }

		//Default values : at the creation of the module, no data has been sent yet and the module has been used for 0 day
	        $duration = 0;
	        $data = 0;

			if ($id > 0) {
				//Delete the previous place
				$reqModule = "DELETE FROM module WHERE id='$id'";
				$ansModule = $conn->query($reqModule);
				//Add the new place
				$reqAdd = "INSERT INTO module (id,installation,place,name,type,description,data,status) VALUES
				  	('$id', '$installation', '$place','$name','$type','$description','$data','$status')";
				$conn->query($reqAdd);
			} else {
				//Update the place
				$reqUpdatePlace = "UPDATE place SET module =module+1 WHERE code='$code'";
				$conn->query($reqUpdatePlace);
				//Add the new place
				$reqAdd = "INSERT INTO module (installation,place,name,type,description,data,status) VALUES
				  	('$installation', '$place','$name','$type','$description','$data','$status')";
				$conn->query($reqAdd);
			}
				
		//Update the module's parameters only if they are known
			if ($temperature) {
				$reqTemperature = "UPDATE module SET temperature=$temperature WHERE name='$name'";
				$conn->query($reqTemperature);
			}
			if ($humidity) {
				$reqHumidity = "UPDATE module SET humidity=$humidity WHERE name='$name'";
				$conn->query($reqHumidity);
			}
			if ($wind) {
				$reqWind = "UPDATE module SET wind=$wind WHERE name='$name'";
				$conn->query($reqWind);
			}
	}

include('head.php');

?>