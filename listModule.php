<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//When a module form has been fulfilled, the module must be added to the database
	include('newModule.php');

	//When a module form has been deleted, the module must be deleted from the database
	include('deleteModule.php');

	//Data shall be displayed from the most recent to the oldest
	$reqModule = "SELECT * FROM module ORDER BY name";
	$ansModule = $conn->query($reqModule);

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

			        <!-- Modules list -->
		      	  	<div class="card shadow mb-4">
	        		<div class="card-header py-3">
	              		<div class="row">
		              		<h3 class="col-md-2 font-weight-bold">Modules</h3>
							<button id="addModuleButton" class="col-md-1 col-md-offset-3 btn btn-primary" onclick="location.href='addModule.php'">Add</button>

					        <input type="text" id="searchBar" class="form-control col-md-offset-5 col-md-3 searchBar" onkeyup="searchTable()" placeholder="Search a module">
			        	</div>
	            	</div>
	            	<div class="card-body">              
				        <table id="table" style="width:100%">
				        	<tr>
				        		<!-- Table header -->
				        		<th onClick="sort(0,'letter')" value="z">Name</th>
				        		<th onClick="sort(1,'letter')" value="z">Place</th>
				        		<th onClick="sort(2,'date')" value="z">Installation</th>
				        		<th onClick="sort(3,'letter')" value="z">Type</th>
				        		<th onClick="sort(4,'letter')" value="z">Description</th>
				        		<th onClick="sort(5,'number')" value="z">Data</th>
				        		<th onClick="sort(6,'number')" value="z">Run time (days)</th>
				        		<th onClick="sort(7,'letter')" value="z">Status</th>
				        	</tr>
				        	<?php
					        	if ($ansModule->num_rows > 0) {
					        		while ($module = $ansModule->fetch_assoc()) {
					        			$id = $module['id'];		
					        			$name = $module['name'];
										$now = time();
										$installationString = strtotime($module["installation"]);
					        			$runTime = round(($now - $installationString)  / (60 * 60 * 24));
					
										echo "<tbody><tr>";
										echo "<td><a href='module.php?name=$name' class='module'>" .$module['name']. "</a></td>";
					        			echo "<td>" .$module['place']. "</td>";
										echo "<td>" .$module['installation']. "</td>";
										echo "<td>" .$module['type']. "</td>";
										echo "<td>" .$module['description']. "</td>";
										echo "<td>" .$module['data']. "</td>";
										echo "<td>" .$runTime. "</td>";
										echo "<td>" .$module['status']. "</td>";
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