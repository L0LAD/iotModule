<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//Get data from the form
	if (isset($_POST['name']) && isset($_POST['code'])) {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$country = $_POST['country'];
		$code = $_POST['code'];
		$module = 0;

		if ($id > 0) {
			$reqModule = "SELECT module FROM place WHERE id='$id'";
			$ansModule = $conn->query($reqModule);
			$module = mysqli_fetch_array($ansModule)[0];

			//Delete the previous place
			$reqPlace = "DELETE FROM place WHERE id='$id'";
			$ansPlace = $conn->query($reqPlace);
		}
			
		//Add the new place
		$reqAdd = "INSERT INTO place (name, country, code, module) VALUES
		  	('$name', '$country','$code','$module')";
		$conn->query($reqAdd);

	}

include('head.php');

?>