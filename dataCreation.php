<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//Updating  modules
		//Disabled modules won't record any data and are not necessary any more
		$reqIDModule = "SELECT id FROM module WHERE status!='disabled'";
		$ansIDModule = $conn->query($reqIDModule);

		$reqNbrModule = "SELECT COUNT(*) FROM module";
		$ansNbrModule = $conn->query($reqNbrModule);
		$nbrModule = mysqli_fetch_array($ansNbrModule)[0];		//Number of modules which are expected to send data

		//First of all, let's consider, no module is down nor in standby
		$reqActivateAll = "UPDATE module SET status='activated' WHERE status!='disabled'";
		$ansActivateAll = $conn->query($reqActivateAll);

		//Random selection of the modules in standby
		$nbrStandby = round($nbrModule/10);			//Estimation : 1 in 10 modules is in standby
		$standby = array();
		for ($i = 0; $i < $nbrStandby; $i++) {
			//Select a random module amongst those avalaible
			$random = rand(0,$nbrModule);
		    $reqIDSelection = "SELECT id FROM module WHERE status!='disabled' LIMIT 1 OFFSET $random";
		    $ansIDSelection = $conn->query($reqIDSelection);
		    $id = mysqli_fetch_array($ansIDSelection)[0];

		    //Put this module in standby mode
		    $reqStandby = "UPDATE module SET status='standby' WHERE id=$id";
			$ansStandby = $conn->query($reqStandby);
		}

		//Random selection of the broken modules
		$nbrDown = round($nbrModule/15);			//Estimation : 1 in 15 modules needs to be repaired
		$down = array();
		for ($i = 0; $i < $nbrDown; $i++) {
			//Select a random module amongst those avalaible
			$random = rand(0,$nbrModule);
		    $reqIDSelection = "SELECT id FROM module WHERE status!='disabled' LIMIT 1 OFFSET $random";
		    $ansIDSelection = $conn->query($reqIDSelection);
		    $id = mysqli_fetch_array($ansIDSelection)[0];

		    //Put this module in down mode
		    $reqDown = "UPDATE module SET status='down' WHERE id=$id";
			$ansDown = $conn->query($reqDown);
		}

	//Creation of new data
		//Select all the new activated modules to generate their data
		$reqActivated = "SELECT * FROM module WHERE status='activated'";
		$ansActivated = $conn->query($reqActivated);
		
		//Generate random data from the usual ones
		if ($ansActivated->num_rows > 0) {
			$nbrActivated = 0;
			while ($module = $ansActivated->fetch_assoc()) {
				$nbrActivated ++;
				$now = date('Y-m-d H:i:s');
				$id = $module['id'];
				$warning = '0';
				$usualTemperature = $module['temperature'];	
				$usualHumidity = $module['humidity'];
				$usualWind = $module['wind'];

				/*Select the previous recorded data for the same module. The new data will be based on it.
				If there isn't such previous data, we shall consider the base values.*/
				$reqPreviousData = "SELECT * FROM data WHERE idModule=$id ORDER BY date DESC LIMIT 1";
				$ansPreviousData = $conn->query($reqPreviousData);
				if ($ansPreviousData->num_rows > 0) {
					while ($previousData = $ansPreviousData->fetch_assoc()) {
						$previousTemperature = $previousData['temperature'];
						$previousHumidity = $previousData['humidity'];
						$previousWind = $previousData['wind'];
					}
				} else {
					$previousTemperature = $usualTemperature;
					$previousHumidity = $usualHumidity;
					$previousWind = $usualWind;
				}

				//Pre-creation of a new data
				$reqNewData = "INSERT INTO data (idModule,date,warning) VALUES
    			('".$id."',
				'".$now."',
				'".$warning."')";
				$ansNewData = $conn->query($reqNewData);

				//Increment the number of data for the module
				$reqIncrement = "UPDATE module SET data = (data+1) WHERE id=$id";
				$ansIncrement = $conn->query($reqIncrement);

				//Select the ID of this data to modify it afterwards
				$reqNewDataID = "SELECT id FROM data ORDER BY id DESC LIMIT 1";
				$ansNewDataID = $conn->query($reqNewDataID);
				$newDataID = mysqli_fetch_array($ansNewDataID)[0];
				
				/*Now we can instanciate the data parameters (temperature, humidity, wind) according to the parameters
				of the previous data (it is unlikely that the temperature increases from 10°C in 10mn for example). If
				there is no previous recorded data, the parameters are based on the expected values for the module.
				Once those new temperature, humidity and wind have been created, they are compared to the expected values.
				If the new parameters are quite close to the expected ones, there is no warning. Otherwise, if those
				differences are too significant, the user (you) should be warned.*/
				$currentTemperature = NULL;
    			if ($usualTemperature) {
    				$currentTemperature = rand($previousTemperature - 1, $previousTemperature + 1);
    				//Values must be coherent
    				if ($currentTemperature < -10) {
    					$currentTemperature = -10;
    				} else if ($currentTemperature > 20) {
    					$currentTemperature = 20;
    				}
    				if (abs($currentTemperature - $usualTemperature)>5) {
    					$warning = 1;
    				}
    				$reqTemperature = "UPDATE data SET temperature=$currentTemperature WHERE id=$newDataID";
					$ansTemperature = $conn->query($reqTemperature);
    			}

				$currentHumidity = NULL;
    			if ($usualHumidity) {
    				$currentHumidity = rand($previousHumidity -5, $previousHumidity + 5);
    				//Values must be coherent
    				if ($currentHumidity < 0) {
    					$currentHumidity = 0;
    				} else if ($currentHumidity > 100) {
    					$currentHumidity = 100;
    				}
    				if (abs($currentHumidity - $usualHumidity)>20) {
    					$warning = 1;
    				}
    				$reqHumidity = "UPDATE data SET humidity=$currentHumidity WHERE id=$newDataID";
					$ansHumidity = $conn->query($reqHumidity);
    			}	

				$currentWind = NULL;
    			if ($usualWind) {
    				$currentWind = rand($previousWind - 8, $previousWind + 8);
    				//Values must be coherent
    				if ($currentWind < 0) {
    					$currentWind = 0;
    				} else if ($currentWind > 110) {
    					$currentWind = 110;
    				}
    				if (abs($currentWind - $usualWind)>35) {
    					$warning = 1;
    				}
    				$reqWind = "UPDATE data SET wind=$currentWind WHERE id=$newDataID";
					$ansWind = $conn->query($reqWind);
    			}

    			//According to the new values of temperature / humidity / wind, the warning might need setting.
    			$reqWarning = "UPDATE data SET warning=$warning WHERE id=$newDataID";
				$ansWarning = $conn->query($reqWarning);
			}
		}