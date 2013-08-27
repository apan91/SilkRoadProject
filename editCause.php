<?php
	require('includes/header.php');
	//TODO: check if the person is an admin 
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
				
			print("<h1>Edit A Cause</h1>");
			//create an array of fields listing the inputs to be changed
			$fields = array('name', 'description', 'goalAmount', 'startDate','endDate', 'uploadedfile');
						
			//new function to return an array of fields with filled in inputs
			function validfilledin($fields) {
				//create an empty array called valid
				$valid = array(); 
				//traverse fields
				foreach($fields as $input) { 
					//if its input is set and not empty insert into valid array
					if($input == "uploadedfile") {
						if(isset($_FILES[$input]) && !empty($_FILES[$input])) { 
							$valid[] = $input;
						}
					}
					else {
						if(isset($_POST[$input]) && !empty($_POST[$input])) { 
							$valid[] = $input;
						}
					}				} 
				//if there is nothing in valid, tell the user to fill in at least one thing to change
					//include the edit profile form and exit
				if(count($valid) < 1) { 
					echo "<p>You must fill in at least one thing to change";
					include('editCauseForm.php');
					die();
				} 
				//else if there is something in the valid array, return it.
				return $valid;
			}
			if (isset($_POST['delete']) && !empty($_POST['delete'])) {
				$cid = $_POST['cid'];
				$edit = $mysqli->query("DELETE FROM Causes WHERE Cause_ID = '$cid';");		
				if($mysqli->errno)
					print($mysqli->error);
				else
					print("<p>Successfully deleted the Cause.</p>");
			}
			
			//check if submitted the form or not
			else if(isset($_POST['submit']) && !empty($_POST['submit'])) {
			
				//check if the user has filled in at least one field to change
				$valid = validfilledin($fields);
				//check if the fields have valid formats again.
				//valid($fields);
				
				$cid = $_POST['cid'];
				
				if(in_array('name', $valid)) {
					$name = $_POST['name'];
					if(trim($name) == '' || !preg_match('/^[A-Za-z0-9\s\-\_]+$/', $name)) {
						echo "<p>Please provide a valid name!</p>";
						include('editCauseForm.php');
						exit();
					}
					$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
					if ($mysqli->errno) {
						print($mysqli->error);
						exit();
					}
					$dup = $mysqli->query("SELECT * FROM Causes WHERE Name = '".$name."'");
					//if there's any error, display the register form again with an error message
					if($mysqli->errno) {
						print($mysqli->error);
						include('editCauseForm.php');
						exit();	
					}
					if($dup->num_rows > 0){
						print("<p>Duplicate cause name.</p>");
						include('editCauseForm.php');
						exit();	
					}
					else {			
					
						$result = $mysqli->query("UPDATE Causes SET Name = '$name' WHERE Cause_ID = '$cid';");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully updated name</p>");
					}
				}
				if(in_array('description', $valid)) {
					$description = $_POST['description'];
					if(trim($description) == '') {
					//validate the description posted again, to make sure there is something as a description.
						echo "<p>Please provide a valid caption!</p>";
						include('editCauseForm.php');
						exit();
					} 
					$result = $mysqli->query("UPDATE Causes SET Caption = '$caption' WHERE Cause_ID = '$did';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated description</p>");
				}
				if(in_array('goalAmount', $valid)) {
					$goalAmount = $_POST['goalAmount'];
					if(trim($goalAmount) == '' || !preg_match('/^[1-9]{1}[0-9]*$/', $goalAmount)) {
					//validate the goal amount posted again, to make sure it is a number (not starting with zero).
						echo "<p>Please provide a valid goal amount!</p>";
						include('editCauseForm.php');
						exit();
					} 
					$result = $mysqli->query("UPDATE Causes SET GoalAmount = '$goalAmount' WHERE Cause_ID = '$cid';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated goal amount.</p>");
				}
				if(in_array('startDate', $valid)) {
					$startDate = $_POST['startDate'];
					$check = datecheck($startDate); //validate startdate
					if($check != "") {
						print($check);
						include('editCauseForm.php');
						exit();
					}
					$result = $mysqli->query("UPDATE Causes SET StartDate = '$startDate' WHERE Cause_ID = '$cid';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated start date.</p>");
				}
				if(in_array('endDate', $valid)) {
					$endDate = $_POST['endDate'];
					$check = datecheck($endDate); //validate startdate
					if($check != "") {
						print($check);
						include('editCauseForm.php');
						exit();
					}
					$result = $mysqli->query("UPDATE Causes SET EndDate = '$endDate' WHERE Cause_ID = '$cid';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated end date.</p>");
				}
				if(in_array('uploadedfile', $valid)) {
					$image_name = basename( $_FILES['uploadedfile']['name']);
			
					//set target path to be uploads/filename.extension
					$target_path =  "covers/".$image_name;
					//move the uploaded file from the temporary location to the target path
					move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);
					
					//set the allowed extensions (only jpeg or jpg allowed)
					$allowedExts = array("jpeg", "jpg");
					$extension = end(explode(".", $_FILES["uploadedfile"]["name"]));
					
					if (!((($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/jpg")) 
							&& in_array(strtolower($extension), $allowedExts))) {
						//check the file's extension and type (image/jpeg or image/jpg)
						echo "<p>Please upload only .jpg files!</p>";
					} 
					else {
						$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
						if ($mysqli->errno) {
							print($mysqli->error);
							exit();
						}
						$dup = $mysqli->query("SELECT * FROM Causes WHERE Cover_URL = 'covers/".$_FILES['uploadedfile']['name']."'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('editCauseForm.php');
							exit();	
						}
						if($dup->num_rows > 0){
							print("<p>Duplicate photo name.</p>");
							include('editCauseForm.php');
							exit();	
						}
						else {						
							$result = $mysqli->query("UPDATE Causes SET Cover_URL = '$target_path' WHERE Cause_ID = '$cid';");
							if($mysqli->errno)
								print($mysqli->error);
							else
								print("<p>Successfully changed the cover photo.</p>");
						}
					}
				}
				include('editCauseForm.php');
			}
			else {
				//if not, show(include) the cause form
				include('editCauseForm.php');
				exit();
			}

			?>
		</div>
	</div>
</div>
</body>
</html>
