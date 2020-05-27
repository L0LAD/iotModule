<?php
session_start();

	$conn = new mysqli("localhost", "root", "", "iot_module");
		if ($conn->connect_error) {
		    die("La connexion à la base de données a échoué: " . $conn->connect_error);
		}

	//Select the module according to the URL
	if(isset($_GET['name'])){
		$_SESSION['name'] = trim($_GET['name']);
		$name = $_SESSION['name'];
	} else {
		$_SESSION['name'] = '';
		echo "Sorry but I don't know this module...";
	};

	//Select the module
	$reqModule = "SELECT * FROM module WHERE name='$name'";
	$ansModule = $conn->query($reqModule);

	//Select the parameters of the module
	while ($module = $ansModule->fetch_assoc()) {
		$id = $module["id"];
		$now = time();
		$installation = $module["installation"];
		$installationString = strtotime($installation);
		$place = $module['place'];
		$type = $module['type'];
		$description = $module['description'];
		$duration = round(($now - $installationString)  / (60 * 60 * 24));
		$data = $module['data'];
		$status = $module['status'];

		//List of all the chosen parameters of the module
		$parameters = array();
		$temperature = $module['temperature'];
		if ($temperature) {
			array_push($parameters, 'temperature');
		}
		$humidity = $module['humidity'];
		if ($humidity) {
			array_push($parameters, 'humidity');
		}
		$wind = $module['wind'];
		if ($wind) {
			array_push($parameters, 'wind');
		}
	}
	
	include('head.php');

?>

<!DOCTYPE html>
<html lang="en">
  <body>

  	<div id="wrapper">

	  	<!-- Sidebar on the left -->
	  	<?php include('sidebar.php') ?>	    

	  	<div id="content-wrapper" class="d-flex flex-column">

		    <!-- Main content -->
		    <div id="content">
			    <div class="container">
			      	
		    		<?php include('header.php') ?>

			        <div class="card shadow mb-4">
		        		<div class="card-header py-3">
		              		<h3>Module <?php echo $name ?>
		              		<button id="editButton" class="btn btn-primary btn-card" onClick="editModule('<?php echo $name ?>')">Edit</button>
		              		<button id="deleteButton" class="btn btn-primary btn-card" onClick="deleteModule('<?php echo $name ?>')">Delete</button>
		              		</h3>

		            	</div>
		            	<div class="card-body">       
		            		<div class="row">       
						        <div class="col-md-6">
				        			<p><b>Place : </b><?php echo $place ?></p>
				        			<p><b>Installation date : </b><?php echo $installation ?> (<?php echo $duration ?> days)</p>
				        			<p><b>Type : </b><?php echo $type ?></p>
				        			<p><b>Number of data sent : </b><?php echo $data ?></p>
				        			<p><b>Status : </b><?php echo $status ?></p>
						        </div>
						        <div class="col-md-6">
				        			<p><b>Temperature (°C) : </b><?php echo $temperature ?></p>
				        			<p><b>Humidity (%) : </b><?php echo $humidity ?></p>
				        			<p><b>Wind (km/h) : </b><?php echo $wind ?></p>
						        </div>
						    </div>
						    <div class="row">
						    	<div class="col-md-12">
				        			<p><b>Description : </b><?php echo $description ?></p>
						        </div>
						    </div>
					    </div>
				    </div>

				    <?php foreach($parameters as $parameter) { ?>
						<div class="card shadow mb-4">
			        		<div class="card-header py-3">
			              		<h3><?php echo $parameter ?></h3>
			            	</div>
			            	<div class="card-body">       
			            		<?php include('graph.php') ?>
						    </div>
					    </div>
					<?php } ?>
				</div>
		    </div>

			<?php include('footer.php') ?>  
	    </div>
	  </div>
	</div> 

	<!-- Javascript -->
	<script type="text/javascript">

	//Alertbox to make sure a module isn't deleted by mistake
	function deleteModule(name){
	    if(confirm("Are you sure you want to delete this module ?")==true)
           window.location="listModule.php?delete="+name;
	}

	/*If the user wants to delete a module, the page will be redirected to addModule.php and a parameter "dit" will be added into the URL
	to pre-fill the form with the parameters of the module*/
	function editModule(name){
    	window.location="addModule.php?edit="+name;
	}
	</script>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>