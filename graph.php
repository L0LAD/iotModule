<?php

	//Load the data from the database
	$idContainer = "chartContainer" .$parameter;
	try {
	    $conn = new \PDO('mysql:host=localhost;dbname=iot_module;charset=utf8mb4',
	                     'root',
	                     '',
	                     array(
	                        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	                        \PDO::ATTR_PERSISTENT => false
	                     )
	    );
		
	    $reqData = $conn->prepare("SELECT * FROM data WHERE idModule='$id' ORDER BY date"); 
	    $reqData->execute(); 
	    $ansData = $reqData->fetchAll(\PDO::FETCH_OBJ);
		
		$temperaturePoints = array();
	    foreach($ansData as $row){
	        array_push($temperaturePoints, array("y"=> $row->temperature, "label"=> $row->date));
	    }

	    $humidityPoints = array();
	    foreach($ansData as $row){
	        array_push($humidityPoints, array("y"=> $row->humidity, "label"=> $row->date));
	    }

	    $windPoints = array();
	    foreach($ansData as $row){
	        array_push($windPoints, array("y"=> $row->wind, "label"=> $row->date));
	    }

		$conn = null;
	}
	catch(\PDOException $ex) {
	    print($ex->getMessage());
	}

?>

<!DOCTYPE HTML>
<html>
	<body>
		<div id="<?php echo $idContainer ?>" style="height: 370px; width: 100%;"></div>
		<script>
			//Chart displayed automatically when the page is loaded
		    window.onload = function () {
		    
		    //Display each chart
		    var temperatureExists = <?php echo $temperature; ?>0;
		    if (temperatureExists) {
			    var idT = "chartContainertemperature";
			    var chart = new CanvasJS.Chart(idT, {
			    	title: {
			    		text: ""
			    	},
			    	axisY: {
			    		title: ""
			    	},
			    	data: [{
			    		type: "line",
			    		dataPoints: <?php echo json_encode($temperaturePoints, JSON_NUMERIC_CHECK); ?>
			    	}]
			    });
			    chart.render();
			};

			var humidityExists = <?php echo $humidity; ?>0;
		    if (humidityExists) {
			    var idH = "chartContainerhumidity";
			    var chart = new CanvasJS.Chart(idH, {
			    	title: {
			    		text: ""
			    	},
			    	axisY: {
			    		title: ""
			    	},
			    	data: [{
			    		type: "line",
			    		dataPoints: <?php echo json_encode($humidityPoints, JSON_NUMERIC_CHECK); ?>
			    	}]
			    });
			    chart.render();
			}

			var windExists = <?php echo $wind; ?>0;
		    if (windExists) {
			    var idW = "chartContainerwind";
			    var chart = new CanvasJS.Chart(idW, {
			    	title: {
			    		text: ""
			    	},
			    	axisY: {
			    		title: ""
			    	},
			    	data: [{
			    		type: "line",
			    		dataPoints: <?php echo json_encode($windPoints, JSON_NUMERIC_CHECK); ?>
			    	}]
			    });
			    chart.render();
		    }
		     
		    }

		</script>

		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	</body>
</html>                              