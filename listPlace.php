<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//When a place form has been fulfilled, the place must be added to the database
	include('newPlace.php');

	//When a place form has been deleted, the place must be deleted from the database
	include('deletePlace.php');

	//Data shall be displayed from the most recent to the oldest
	$reqPlace = "SELECT * FROM place ORDER BY name";
	$ansPlace = $conn->query($reqPlace);

?>

<!DOCTYPE html>
<html lang="en">

	<?php include('head.php'); ?>

  <body>
	<!-- Script to sort the talble -->
  	<script src="js/table.js"></script> 

	<div id="wrapper">

	  	<!-- Sidebar on the left -->
	  	<?php include('sidebar.php') ?>	    

	  	<div id="content-wrapper" class="d-flex flex-column">

		    <!-- Main content -->
		    <div id="content">
			    <div class="container">
			      	
		    		<?php include('header.php') ?>

			        <!-- Places list -->
		      	  	<div class="card shadow mb-4">
	        		<div class="card-header py-3">
	              		<div class="row">
		              		<h3 class="col-md-2 font-weight-bold">Places</h3>
							<button id="addPlaceButton" class="col-md-1 col-md-offset-3 btn btn-primary" onclick="location.href='addPlace.php'">Add</button>

					        <input type="text" id="searchBar" class="form-control col-md-offset-5 col-md-3 searchBar" onkeyup="searchTable()" placeholder="Search a place">
			        	</div>
	            	</div>
	            	<div class="card-body">              
				        <table id="table" style="width:100%">
				        	<tr>
				        		<th onClick="sort(0,'letter')" value="z">Code</th>
				        		<th onClick="sort(1,'letter')" value="z">Name</th>
				        		<th onClick="sort(2,'letter')" value="z">Country</th>
				        		<th onClick="sort(3,'number')" value="z">Number of modules</th>
				        		<th onClick="sort(4,'number')" value="z">Number of warnings</th>
				        	</tr>
				        	<?php
					        	if ($ansPlace->num_rows > 0) {
					        		while ($place = $ansPlace->fetch_assoc()) {
					        			$id = $place['id'];	
					        			$code = $place['code'];	
					        			$name = $place['name'];

					        			$reqWarning = "SELECT COUNT(*) FROM data JOIN module ON module.id=data.idModule WHERE place='$name' AND warning=1";
										$ansWarning = $conn->query($reqWarning);
										$warnings = mysqli_fetch_array($ansWarning)[0];
					
										echo "<tbody><tr>";
										echo "<td><a href='place.php?code=$code' class='place'>" .$place['code']. "</a></td>";
					        			echo "<td>" .$place['name']. "</td>";
										echo "<td>" .$place['country']. "</td>";
										echo "<td>" .$place['module']. "</td>";
										echo "<td>" .$warnings. "</td>";

										echo "</tr></tbody>";
									}
								}
				        	?>
				        </table>
				    </div>
			 	   	</div>
		    	</div>
	    	</div>  

    	<?php include('footer.php') ?>

	  	</div>
	  	
	</div>

    <!-- Necessary script to make sure datas aren't send multiples times into the database if the page is refreshed -->
    <script>
	    if (window.history.replaceState) {
	        window.history.replaceState(null, null, window.location.href);
	    }
	</script>
  </body>

</html>