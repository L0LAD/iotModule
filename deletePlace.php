<?php
session_start();

	$conn = new mysqli("localhost", "root", "", "iot_module");
		if ($conn->connect_error) {
		    die("La connexion à la base de données a échoué: " . $conn->connect_error);
		}

	//Select the place according to the URL
	if(isset($_GET['delete'])){
		$_SESSION['delete'] = trim($_GET['delete']);
		$code = $_SESSION['delete'];

		//Select the place
		$reqSelect = "SELECT name FROM place WHERE code='$code'";
		$ansSelect = $conn->query($reqSelect);
		$name = mysqli_fetch_array($ansSelect)[0];

		//Delete the place
		$reqPlace = "DELETE FROM place WHERE code='$code'";
		$ansPlace = $conn->query($reqPlace);

		//Delete the module from this place
		$reqModule = "DELETE FROM module WHERE place='$name'";
		$ansModule = $conn->query($reqModule);

		//Delete the data related to this place
		$reqData = "DELETE FROM data JOIN module ON module.id = idModule WHERE module.place='$name'";
		$ansData = $conn->query($reqData);
	};

