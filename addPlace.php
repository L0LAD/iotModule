<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	//If a place is specified in the URL, it means it has to be modified and not added again.
	if(isset($_GET['edit'])){
		$code = trim($_GET['edit']);
		$edit = 1;

		//Get the current place from the code
		$reqPlaceBefore = "SELECT * FROM place WHERE code='$code'";
		$ansPlaceBefore = $conn->query($reqPlaceBefore);
		while ($place = $ansPlaceBefore->fetch_assoc()) {
			$id = $place["id"];
			$name = $place["name"];
			$country = $place['country'];
		}
	} else {
		$edit = 1;
		$id = 0;
		$name = "";
		$code = "";
		$country = "";
	}
	echo $id;

?>

<!DOCTYPE html>
<html lang="en">
  <body onload="edit()">

  	<?php include('head.php'); ?>

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
		              		<h3 class="m-0 font-weight-bold">Edit place</h3>
		            	</div>
		            	<div class="card-body">  

							<form class="form-horizontal" method="post" action="../iotModule/listPlace.php">
								<h2>Informations</h2>

								<div class="form-group" hidden>
									<div>
										<input id="idField" type="text" class="form-control" class="form-control" name="id">
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Name *</label>
									</div>
									<div>
										<input id="nameField" type="text" class="form-control" class="form-control" name="name" onChange="fields_valid()">
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Code *</label>
									</div>
									<div>
										<input id="codeField" type="text" class="form-control" class="form-control" name="code" onChange="fields_valid()">
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Country</label>
									</div>
									<div>
										<input id="countryField" type="text" class="form-control" class="form-control" name="country">
									</div>
								</div>

							<div class="right row">
								<button id="addButton" class="btn btn-primary" type="submit" disabled>Edit</button>
					        </div>

							</form>
					    </div>
					</div>
			    </div>
			</div>
			<?php include('footer.php') ?>
		</div>
	</div>
		

<script type="text/javascript">

	/*It should not be possible to add a name if the required values (*) are still empty, consequently
	the "addButton" should not be clickable.*/
	function fields_valid() {
		var name = document.getElementById('nameField').value;
		var code = document.getElementById('codeField').value;
		if (name!="" && code!="") {
			document.getElementById('addButton').disabled = false;
		}
	}

	/*If a place is specified in the URL, its former data will be collected to pre-fill the form.
	Consequently the user won't have to fulfill the whole form again.*/
	function edit() {
		var edit = <?php echo $edit; ?>;
		if (edit == 1) {
			var blank = '';

			document.getElementById('addButton').disabled = false;

			var id = '<?php echo $id; ?>'.concat(blank, blank);
			if (id) {
				document.getElementById('idField').value = id;	
			}

			var name = '<?php echo $name; ?>'.concat(blank, blank);
			if (name) {
				document.getElementById('nameField').value = name;	
			}

			var code = '<?php echo $code; ?>'.concat(blank, blank);
			if (code) {
				document.getElementById('codeField').value = code;	
			}

			var country = '<?php echo $country; ?>'.concat(blank, blank);
			if (country) {
				document.getElementById('countryField').value = country;	
			}
		}
	}
</script>

</body>