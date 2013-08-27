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
		
			print("<h1>Edit A Project</h1>");
			//create an array of fields listing the inputs to be changed
			$fields = array('name', 'caption', 'PCF', 'uploadedfile');
			
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
					}
				} 
				//if there is nothing in valid, tell the user to fill in at least one thing to change
					//include the edit profile form and exit
				if(count($valid) < 1) { 
					echo "<p>You must fill in at least one thing to change";
					include('editProjectForm.php');
					die();
				} 
				//else if there is something in the valid array, return it.
				return $valid;
			}
			
			if (isset($_POST['delete']) && !empty($_POST['delete'])) {
				$pid = $_POST['pid'];
				$edit = $mysqli->query("DELETE FROM Projects WHERE Project_ID = '$pid';");		
				if($mysqli->errno)
					print($mysqli->error);
				else
					print("<p>Successfully deleted the project.</p>");
			}
			
			//check if submitted the form or not
			else if(isset($_POST['submit']) && !empty($_POST['submit'])) {
			
				//check if the user has filled in at least one field to change
				$valid = validfilledin($fields);
				//check if the fields have valid formats again.
				//valid($fields);
				
				$pid = $_POST['pid'];
				
				if(in_array('name', $valid)) {
					$name = $_POST['name'];
					if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
						echo "<p>Please provide a valid name!</p>";
						include('editProjectForm.php');
						exit();
					} 
					$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
					if ($mysqli->errno) {
						print($mysqli->error);
						exit();
					}
					$dup = $mysqli->query("SELECT * FROM Projects WHERE Name = '".$name."'");
					//if there's any error, display the register form again with an error message
					if($mysqli->errno) {
						print($mysqli->error);
						include('editProjectForm.php');
						exit();	
					}
					if($dup->num_rows > 0){
						print("<p>Duplicate cause name.</p>");
						include('editProjectForm.php');
						exit();	
					}
					else {		
						$result = $mysqli->query("UPDATE Projects SET Name = '$name' WHERE Project_ID = '$pid';");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully updated name</p>");
					}
				}
				if(in_array('caption', $valid)) {
					$caption = $_POST['caption'];
					if(trim($caption) == '') {
					//validate the caption posted again, to make sure there is something as a caption.
						echo "<p>Please provide a valid caption!</p>";
						include('editProjectForm.php');
						exit();
					} 
					$result = $mysqli->query("UPDATE Projects SET Caption = '$caption' WHERE Project_ID = '$pid';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated caption</p>");
				}
				if(in_array('PCF', $valid)) {
					$PCF = $_POST['PCF'];
					$result = $mysqli->query("UPDATE Projects SET PCF = '$PCF' WHERE Project_ID = '$pid';");
					if($mysqli->errno)
						print($mysqli->error);
					else
						print("<p>Successfully updated a past, current, future.</p>");
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
						$dup = $mysqli->query("SELECT * FROM Projects WHERE Cover_URL = 'covers/".$_FILES['uploadedfile']['name']."'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('editProjectForm.php');
							exit();	
						}
						if($dup->num_rows > 0){
							print("<p>Duplicate photo name.</p>");
							include('editProjectForm.php');
							exit();	
						}
						else {				
							$result = $mysqli->query("UPDATE Projects SET Cover_URL = '$target_path' WHERE Project_ID = '$pid';");
							if($mysqli->errno)
								print($mysqli->error);
							else
								print("<p>Successfully changed the cover photo.</p>");
						}
					}
				}
				include('editProjectForm.php');
			}
			else {
				//if not, show(include) the cause form
				include('editProjectForm.php');
				exit();
			}

			?>
		</div>
	</div>
</div>
</body>
</html>
