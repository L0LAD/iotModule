<?php
session_start();

	$conn = new mysqli("localhost", "root", "", "iot_module");
		if ($conn->connect_error) {
		    die("La connexion à la base de données a échoué: " . $conn->connect_error);
		}

	//Select the place according to the URL
	if(isset($_GET['code'])){
		$_SESSION['code'] = trim($_GET['code']);
		$code = $_SESSION['code'];
	} else {
		$_SESSION['code'] = '';
		echo "Sorry but I don't know this place...";
	};

	//Select the place
	$reqPlace = "SELECT * FROM place WHERE code='$code'";
	$ansPlace = $conn->query($reqPlace);

	//Select the parameters of the place
	while ($place = $ansPlace->fetch_assoc()) {
		$id = $place["id"];
		$name = $place["name"];
		$country = $place["country"];
		$module = $place['module'];
	}

	$reqData = "SELECT COUNT(*) FROM data JOIN module ON module.id=data.idModule WHERE place='$name'";
	$ansData = $conn->query($reqData);
	$data = mysqli_fetch_array($ansData)[0];

	$reqWarning = "SELECT COUNT(*) FROM data JOIN module ON module.id=data.idModule WHERE place='$name' AND warning=1";
	$ansWarning = $conn->query($reqWarning);
	$warnings = mysqli_fetch_array($ansWarning)[0];

	$reqActivated = "SELECT COUNT(*) FROM module WHERE place='$name' AND status='activated'";
	$ansActivated = $conn->query($reqActivated);
	$moduleActivated = mysqli_fetch_array($ansActivated)[0];

	$reqStandby = "SELECT COUNT(*) FROM module WHERE place='$name' AND status='standby'";
	$ansStandby = $conn->query($reqStandby);
	$moduleStandby = mysqli_fetch_array($ansStandby)[0];

	$reqDisabled = "SELECT COUNT(*) FROM module WHERE place='$name' AND status='disabled'";
	$ansDisabled = $conn->query($reqDisabled);
	$moduleDisabled = mysqli_fetch_array($ansDisabled)[0];

	$reqDown = "SELECT COUNT(*) FROM module WHERE place='$name' AND status='down'";
	$ansDown = $conn->query($reqDown);
	$moduleDown = mysqli_fetch_array($ansDown)[0];	
	
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
		              		<h3><?php echo $name ?>
		              		<button id="editButton" class="btn btn-primary btn-card" onClick="editPlace('<?php echo $code ?>')">Edit</button>
		              		<button id="deleteButton" class="btn btn-primary btn-card" onClick="deletePlace('<?php echo $code ?>')">Delete</button>
		              		</h3>

		            	</div>
		            	<div class="card-body">       
		            		<div class="row">       
						        <div class="col-md-6">
				        			<p><b>Code : </b><?php echo $code ?></p>
				        			<p><b>Country : </b><?php echo $country ?></p>
				        			<p><b>Number of data sent : </b><?php echo $data ?></p>
				        			<p><b>Number of warnings : </b><?php echo $warnings ?></p>
						        </div>
						        <div class="col-md-6">
				        			<p><b>Number of modules : </b><?php echo $module ?></p>
				        			<p><b style="color:green">Activated : </b><?php echo $moduleActivated ?></p>
				        			<p><b style="color:brown">Standby : </b><?php echo $moduleStandby ?></p>
				        			<p><b style="color:gray">Disabled : </b><?php echo $moduleDisabled ?></p>
				        			<p><b style="color:red">Down : </b><?php echo $moduleDown ?></p>
						        </div>
						    </div>
					    </div>
				    </div>

				</div>
		    </div>

			<?php include('footer.php') ?>  
	    </div>
	  </div>
	</div> 

	<!-- Javascript -->
	<script type="text/javascript">

	//Alertbox to make sure a place isn't deleted by mistake
	function deletePlace(code){
	    if(confirm("Are you sure you want to delete this place ?")==true)
        	window.location="listPlace.php?delete="+code;
       		//Even if a site is deleted, the modules and data related won't be destroyed too, it might be useful to keep records of them
	}

	/*If the user wants to delete a place, the page will be redirected to addPlace.php and a parameter "dit" will be added into the URL
	to pre-fill the form with the parameters of the place*/
	function editPlace(code){
    	window.location="addPlace.php?edit="+code;
	}
	</script>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>