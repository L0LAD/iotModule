<?php

	$conn = new mysqli("localhost", "root", "", "iot_module");
	if ($conn->connect_error) {
	    die("La connexion à la base de données a échoué: " . $conn->connect_error);
	}

	$reqPlace = "SELECT DISTINCT name FROM place ORDER BY name";
	$ansPlace = $conn->query($reqPlace);

	//If a module is specified in the URL, it means it has to be modified and not added again.
	if(isset($_GET['edit'])){
		$_SESSION['edit'] = trim($_GET['edit']);
		$name = $_SESSION['edit'];
		$edit = 1;

		//Get the current data from the module
		$reqModuleBefore = "SELECT * FROM module WHERE name='$name'";
		$ansModuleBefore = $conn->query($reqModuleBefore);
		while ($module = $ansModuleBefore->fetch_assoc()) {
			$id = $module["id"];
			$place = $module["place"];
			$type = $module["type"];
			$description = $module['description'];
			$status = $module["status"];

			if ($module["temperature"]) {
				$temperature = $module["temperature"];
			} else {
				$temperature = 0;
			}
			if ($module["humidity"]) {
				$humidity = $module['humidity'];
			} else {
				$humidity = 0;
			}
			if ($module["wind"]) {
				$wind = $module['wind'];
			} else {
				$wind = 0;
			}
		}
	} else {		//If no module is specified in the URL, the variables shall be initialized as null
		$edit = 0;
		$id = 0;
		$name = "";
		$place = "";
		$type = "";
		$description = "";
		$status = "";

		$temperature = 0;
		$humidity = 0;
		$wind = 0;
	}

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
		              		<h3 class="m-0 font-weight-bold">Edit module</h3>
		            	</div>
		            	<div class="card-body">  

							<form class="form-horizontal" method="post" action="../iotModule/listModule.php">
								<h2>Informations</h2>

								<div class="form-group" hidden>
									<div>
										<input id="idField" type="text" class="form-control" class="form-control" name="id">
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Place *</label>
									</div>
									<div>
										<select id="placeSelect" class="form-control" name="place" onChange="fields_valid()">
											<option value="empty"></option>
											<?php
											while ($row = $ansPlace->fetch_assoc()) {
												$name = $row["name"];
												echo "<option value='$name'>$name</option>";
											} ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Type *</label>
									</div>
									<div>
										<div>
											<div>
												<select id="typeSelect" class="form-control" name="type" onChange="fields_valid()">
													<option value="empty"></option>
													<option value="Bluetooth">Bluetooth</option>
													<option value="Wifi">Wifi</option>
													<option value="3G">3G</option>
													<option value="4G">4G</option>
													<option value="5G">5G</option>
												</select>
											</div>
										</div>
									</div>

								</div>
								<div class="form-group">
									<div class="col-md-3 col-sm-11">
										<label>Description</label>
									</div>
									<div>
										<input id="descriptionField" type="text" class="form-control" class="form-control" name="description">
									</div>
								</div>

								<h2>Data</h2>
								<div >
									<div class="form-group">
										<div class="col-md-3 col-sm-11">
											<label>Data type</label>
										</div>
										<div class="row col-md-offset-1">
											<div class="col-md-4 col-sm-11">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" id="temperatureCheck" name="temperatureCheck" class="form-check-input" value="temperature" onclick="checkbox_changed(this, 'temperatureRow')" unchecked="unchecked">Temperature
													</label>
												</div>
											</div>
											<div class="col-md-4 col-sm-11">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" id="humidityCheck" name="humidityCheck" class="form-check-input" value="humidity" onclick="checkbox_changed(this, 'humidityRow')" unchecked="unchecked">Humidity
													</label>
												</div>	
											</div>
											<div class="col-md-4 col-sm-11">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" id="windCheck" name="windCheck" class="form-check-input" value="wind" onclick="checkbox_changed(this, 'windRow')" unchecked="unchecked">Wind
													</label>
												</div>
											</div>
										</div>
									</div>

							        <div class="form-group" id="temperatureRow" style="display:none">
										<div class="col-md-3 col-sm-11">
											<label>Reference temperature (°C)</label>
										</div>
										<div>
											<input type="text" class="form-control" id="temperatureInput" name="temperature">
										</div>
									</div>

									<div class="form-group" id="humidityRow" style="display:none">
										<div class="col-md-3 col-sm-11">
											<label>Reference humidity (%)</label>
										</div>
										<div>
											<input type="text" class="form-control" id="humidityInput" name="humidity">
										</div>
									</div>

									<div class="form-group" id="windRow" style="display:none">
										<div class="col-md-3 col-sm-11">
											<label>Reference wind (km/h)</label>
										</div>
										<div>
											<input type="text" class="form-control" id="windInput" name="wind">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-3 col-sm-11">
											<label>Status *</label>
										</div>
										<div>
											<select id="statusSelect" class="form-control" name="status" onChange="fields_valid()">
												<option value="empty"></option>
												<option value="Activated">Activated</option>
												<option value="Standby">Standby</option>
												<option value="Disabled">Disabled</option>
												<option value="Down">Down</option>
											</select>
										</div>
								</div>
							</form>
							<div class="right row">
								<button id="addButton" class="btn btn-primary" type="submit" disabled>Edit</button>
					        </div>
					    </div>
					</div>
			    </div>
			</div>
			<?php include('footer.php') ?>
		</div>
	</div>
		

<script type="text/javascript">

	//If a checkbox is checked (for example temperatureCheck) is checked the corresponding field will be displayed (for example temperatureField)
	function checkbox_changed(termsCheckBox, id) {
	    //If the checkbox has been checked
	    if(termsCheckBox.checked) {
    		//Set the disabled property to FALSE and enable the line
	        document.getElementById(id).style.display = "";
	    } else {
    		//Otherwise, disable the line
	        document.getElementById(id).style.display = "none";
	    }
	}

	/*It should not be possible to add a module if the required values (*) are still empty, consequently
	the "addButton" should not be clickable.*/
	function fields_valid() {
		var placeSelect = document.getElementById('placeSelect');
		var typeSelect = document.getElementById('typeSelect');
		var statusSelect = document.getElementById('statusSelect');
		var place = placeSelect.options[placeSelect.selectedIndex].value;
		var type = typeSelect.options[typeSelect.selectedIndex].value;
		var status = statusSelect.options[statusSelect.selectedIndex].value;
		if (place!='empty' && type!='empty' && status!='empty') {
			document.getElementById('addButton').disabled = false;
		}
	}

	/*If a module is specified in the URL, its former data will be collected to pre-fill the form.
	Consequently the user won't have to fulfill the whole form again.*/
	function edit() {
		var edit = <?php echo $edit; ?>;
		if (edit == 1) {
			document.getElementById('addButton').disabled = false;

			var blank = '';

			var id = '<?php echo $id; ?>'.concat(blank, blank);
			if (id) {
				document.getElementById('idField').value = id;	
			}

			var placeSelect = document.getElementById('placeSelect');
			var places = placeSelect.options;
			for (var opt, j = 0; opt = places[j]; j++) {
				if (opt.value == "<?php echo $place ?>") {
				  placeSelect.selectedIndex = j;
				  break;
				}			
			}	

			var typeSelect = document.getElementById('typeSelect');
			var type = typeSelect.options;
			for (var opt, j = 0; opt = type[j]; j++) {
				if (opt.value == "<?php echo $type ?>") {
				  typeSelect.selectedIndex = j;
				  break;
				}
			}

			var statusSelect = document.getElementById('statusSelect');
			var status = statusSelect.options;
			for (var opt, j = 0; opt = status[j]; j++) {
				if (opt.value == "<?php echo $status ?>") {
				  statusSelect.selectedIndex = j;
				  break;
				}
			}

			var description = '<?php echo $description; ?>'.concat(blank, blank);
			if (description) {
				document.getElementById('descriptionField').value = description;	
			}

			var temperature = <?php echo $temperature; ?>;
			if (temperature) {
				document.getElementById('temperatureCheck').checked = true;
				document.getElementById('temperatureRow').style.display = "";
				document.getElementById('temperatureInput').value = temperature;	
			}

			var humidity = <?php echo $humidity; ?>;
			if (humidity) {
				document.getElementById('humidityCheck').checked = true;
				document.getElementById('humidityRow').style.display = "";
				document.getElementById('humidityInput').value = humidity;			
			}

			var wind = <?php echo $wind; ?>;
			if (wind) {
				document.getElementById('windCheck').checked = true;
				document.getElementById('windRow').style.display = "";
				document.getElementById('windInput').value = wind;	
			}
		}
	}
</script>

</body>