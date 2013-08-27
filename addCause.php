<?php
	require('includes/header.php');
?>
<body>
<div id="container">
	<div id="masthead">
	<?php
		require('includes/menu.php');
	?>
	</div>
	<div id="content">
		<div id="content_inner">
			<?php
				include('adminCheck.php');					

				
				function datecheck($date) {
					$dateformat = '/^[0-9]{1,4}-[0-9]{1,2}-[0-9]{1,2}$/';
					if(preg_match($dateformat, $date)) { //date format (YYYY-MM-DD)
						$datetemp = explode('-',$date);
						if(!(checkdate($datetemp[1], $datetemp[2], $datetemp[0]))) // valid date
							return "<p>the date entered (".$date.") is not valid.</p>";
						else
							return "";
					}
					else
						return "<p>date entered (".$date.") does not match correct format YYYY-MM-DD.</p>";					
					
				}
				
				$dupfields = array('name', 'photoname');
				function duplicates($dupfields) {
					$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
					if ($mysqli->errno) {
						print($mysqli->error);
						exit();
					}
					//create an empty array of invalid inputs
					$invalid = array(); 
					//travers the fields array
						//if anything's not set of empty, insert to invalid array
					foreach($dupfields as $input) { 
						if($input == 'name')
							$dup = $mysqli->query("SELECT * FROM Causes WHERE Name = '".$_POST['name']."'");
						else
							$dup = $mysqli->query("SELECT * FROM Causes WHERE Cover_URL = 'covers/".$_FILES['uploadedfile']['name']."'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('addCauseForm.php');
							exit();	
						}
						if($dup->num_rows > 0){
							$invalid[] = $input;
						}
					} 
					//if there is anything in invalid array
						//display all the items in invalid array and tell the user to fill in those parts
						//show the register form again
					if(count($invalid) > 0) { 
						echo "<p>Duplicate entries for: ";
						echo implode($invalid, ", ");
						echo "</p>";
						include('addCauseForm.php');
						die();
					} 
				}
			
				
			print("<h1>Add Cause</h1>");
			
			//check if submitted the form or not
			if(!(isset($_POST['submit']) && !empty($_POST['submit']))) {
				//if not, show(include) the cause form
				include('addCauseForm.php');
				exit();
			}
			//else if submitted the form
			//check if uploaded a file, if not tell the user to select a file
			if(!isset($_FILES['uploadedfile'])) {
				echo "<p>Please select a file to upload</p>";
				include('addCauseForm.php');
				exit();
			} else {
				$image_name = basename( $_FILES['uploadedfile']['name']);
				
				//set target path to be uploads/filename.extension
				$target_path =  "covers/".$image_name;
				//set the allowed extensions (only jpeg or jpg allowed)
				$allowedExts = array("jpeg", "jpg");
				$extension = end(explode(".", $_FILES["uploadedfile"]["name"]));
				//get name and description
				//use mysql_real_escap_string to ensure the input
				$name = mysql_real_escape_string($_POST['name']);
				$description = mysql_real_escape_string($_POST['description']);
				//check if goal amount is posted, if not tell the user to enter a goal amount.
				if(!(isset($_POST['goalAmount']) && !empty($_POST['goalAmount']))) {
					echo "<p>Please enter a goal amount</p>";
					include('addCauseForm.php');
					exit();
				} 
				$goalAmount = $_POST['goalAmount'];
				//check if the start date is set, if not tell the user to enter a start date
				if(!(isset($_POST['startDate']) && !empty($_POST['startDate']))) {
					echo "<p>Please enter a start date</p>";
					include('addCauseForm.php');
					exit();
				} 
				$startDate = $_POST['startDate'];
				//check if the end date is set, if not tell the user to enter an end date
				if(!(isset($_POST['endDate']) && !empty($_POST['endDate']))) {
					echo "<p>Please enter an end date</p>";
					include('addCauseForm.php');
					exit();
				} 
				$endDate = $_POST['endDate'];
				$check = datecheck($startDate); //validate startdate
				if($check != "") {
					print($check);
					include('addCauseForm.php');
					exit();
				}
				$check = datecheck($endDate); //validate enddate
				if($check != "") {
					print($check);
					include('addCauseForm.php');
					exit();
				}				
				//validate the name posted again, to make sure only texts, numbers, -, _ or spaces are in name.
				if(trim($name) == '' || !preg_match('/^[A-Za-z0-9\s\-\_]+$/', $name)) {
					echo "<p>Please provide a valid name!</p>";
					include('addCauseForm.php');
					exit();
				} else if(trim($description) == '') {
				//validate the description posted again, to make sure there is something as a description.
					echo "<p>Please provide a valid caption!</p>";
					include('addCauseForm.php');
					exit();
				} else if(trim($goalAmount) == '' || !preg_match('/^[1-9]{1}[0-9]*$/', $goalAmount)) {
				//validate the goal amount posted again, to make sure it is a number (not starting with zero).
					echo "<p>Please provide a valid goal amount!</p>";
					include('addCauseForm.php');
					exit();
				} else if (!((($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/jpg")) 
						   && in_array(strtolower($extension), $allowedExts))) {
				//check the file's extension and type (image/jpeg or image/jpg)
					echo "<p>Please upload only .jpg files!</p>";
					include('addCauseForm.php');
					exit();
				} 
				//validate the start date and end date, to make sure it is in correct format and the date is a valid date
				else {
					//check duplicates
					duplicates($dupfields);
				
					//if all of the above validations suceed
					//move the uploaded file from the temporary location to the target path
					move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);

					
					// Insert the cause into Causes database table.
					$query = "INSERT INTO Causes(Name, Description, GoalAmount, StartDate, EndDate, Cover_URL) VALUES('$name', '$description', '$goalAmount', '$startDate', '$endDate', '$target_path');";
					$result = $mysqli->query($query);
					//if there is an error, exit
					if($mysqli->errno) {
						print($mysqli->error);
						include('addCauseForm.php');
						exit();	
					}
					//else print a succeed message and show the form to add another cause.
					echo "<p>Successfully added a Cause</p>";
					include('addCauseForm.php');
					exit();	
				}
			}
			
			?>
		</div>
	</div>
</div>
</body>
</html>
