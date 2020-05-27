<?php
session_start();

	$conn = new mysqli("localhost", "root", "", "iot_module");
		if ($conn->connect_error) {
		    die("La connexion à la base de données a échoué: " . $conn->connect_error);
		}

	//Select the module according to the URL
	if(isset($_GET['delete'])){
		$_SESSION['delete'] = trim($_GET['delete']);
		$name = $_SESSION['delete'];

		//Delete the module
		$reqModule = "DELETE FROM module WHERE name='$name'";
		$ansModule = $conn->query($reqModule);
		
		//Delete the data related to the module
		$reqData = "DELETE FROM data JOIN module ON module.id = idModule WHERE module.name='$name'";
		$ansData = $conn->query($reqData);
	};

