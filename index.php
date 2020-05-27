
<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//Data are generated every time the page is refreshed
	include('dataCreation.php');

	//Data shall be displayed from the most recent to the oldest
	$reqData = "SELECT * FROM data ORDER BY date DESC LIMIT 10";
	$ansData = $conn->query($reqData);

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

			        <!-- Dysfunctional modules should be highlighted -->
			        <div class="card shadow mb-4">
		        		<div class="card-header py-3">
		              		<h3>Dysfunctional modules</h3>
		            	</div>
		            	<div class="card-body">              
					        <table id="alertTable" style="width:100%">
					        	<?php
					        		$reqProblem = "SELECT * FROM module WHERE status='down'";
									$ansProblem = $conn->query($reqProblem);
						        	if ($ansProblem->num_rows > 0) {
						        		echo "<ul>";
						        		while ($module = $ansProblem->fetch_assoc()) {
						        			$id = $module['id'];
						        			$name = $module['name'];
											$reqLastSent = "SELECT date FROM data WHERE idModule=$id ORDER BY date DESC LIMIT 1";
											$ansLastSent = $conn->query($reqLastSent);
											$lastSent = mysqli_fetch_array($ansLastSent)[0];

											echo "<li>";
											echo "<a href='module.php?name=$name' class='module'>" .$module['name']. "</a>";
											echo " (" .$module['place']. ", ";
											echo $module['type']. ") ";
											if ($lastSent) {
												echo "since <b>" .$lastSent. "</b></li>";												
											} else {
												echo "has <b>never</b> worked";
											}
										}
										echo "</ul>";
									} else {
										echo "No module down";
									}
					        	?>
					        </table>
					    </div>
				    </div>

		       		<!-- Recent data -->
		      	  	<div class="card shadow mb-4">
		        		<div class="card-header py-3">
		              		<h3 class="m-0">Recent data</h3>
		            	</div>
		            	<div class="card-body">              
					        <table id="table" style="width:100%">
					        	<tr>
					        		<!-- Table header -->
					        		<th onClick="sort(0,'datetime')" value="z">Record</th>
					        		<th onClick="sort(1,'letter')" value="z">Name</th>
					        		<th onClick="sort(2,'letter')" value="z">Place</th>
					        		<th onClick="sort(3,'number')" value="z">Type</th>
					        		<th onClick="sort(4,'number')" value="z">Temperature (°C)</th>
					        		<th onClick="sort(5,'number')" value="z">Humidity (%)</th>
					        		<th onClick="sort(6,'number')" value="z">Wind (km/h)</th>
					        	</tr>
					        	<?php
						        	if ($ansData->num_rows > 0) {
						        		while ($rowData = $ansData->fetch_assoc()) {
						        			$idModule = $rowData['idModule'];		
						        			$warning = $rowData['warning'];
						        			$reqModule = "SELECT * FROM module WHERE id = $idModule";
											$ansModule = $conn->query($reqModule);
						
											echo "<tbody><tr>";
											echo "<td>" .$rowData['date']. "</td>";
											while ($rowModule = $ansModule->fetch_assoc()) {
												$name = $rowModule['name'];
												if ($warning) {
													echo "<td><a href='module.php?name=$name' class='module alert'>" .$name. "</a></td>";
												} else {
													echo "<td><a href='module.php?name=$name' class='module'>" .$name. "</a></td>";
												}
												echo "<td>" .$rowModule['place']. "</td>";
												echo "<td>" .$rowModule['type']. "</td>";
						        			}
						        			echo "<td>" .$rowData['temperature']. "</td>";
											echo "<td>" .$rowData['humidity']. "</td>";
											echo "<td>" .$rowData['wind']. "</td>";
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

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>

</html>