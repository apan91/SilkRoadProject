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
							$dup = $mysqli->query("SELECT * FROM Projects WHERE Name = '".$_POST['name']."'");
						else
							$dup = $mysqli->query("SELECT * FROM Projects WHERE Cover_URL = 'covers/".$_FILES['uploadedfile']['name']."'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('addProjectForm.php');
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
						include('addProjectForm.php');
						die();
					} 
				}
			
			print("<h1>Add Project</h1>");
			//check if submitted the form or not
			if(!(isset($_POST['submit']) && !empty($_POST['submit']))) {
				//if not, show(include) the cause form
				include('addProjectForm.php');
				exit();
			}
			//else if submitted the form
			//check if uploaded a file, if not tell the user to select a file
			if(!isset($_FILES['uploadedfile'])) {
				echo "<p>Please select a file to upload</p>";
				include('addProjectForm.php');
				exit();
			} else {
			//if uploaded a file,	
				$image_name = basename($_FILES['uploadedfile']['name']);
			
				//set target path to be uploads/filename.extension
				$target_path =  "covers/".$image_name;
				//set the allowed extensions (only jpeg or jpg allowed)
				$allowedExts = array("jpeg", "jpg");
				$extension = end(explode(".", $_FILES["uploadedfile"]["name"]));
				//get name and caption
				//use mysql_real_escap_string to ensure the input
				$name = mysql_real_escape_string($_POST['name']);
				$caption = mysql_real_escape_string($_POST['caption']);
				//check if Past/Current/Future option is selected
				if(!(isset($_POST['PCF']) && !empty($_POST['PCF']))) {
					//if not tell the user to choose either Past,Current,orFuture.
					echo "<p>Please choose either Past, Current, or Future!</p>";
					include('addProjectForm.php');
					exit();
				} 
				
				$PCF = $_POST['PCF'];
				//validate the name posted again, to make sure only texts, numbers, -, _ or spaces are in name.
				if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>Please provide a valid name!</p>";
					include('addProjectForm.php');
					exit();
				} else if(trim($caption) == '') {
				//validate the caption posted again, to make sure there is something as a caption.
					echo "<p>Please provide a valid caption!</p>";
					include('addProjectForm.php');
					exit();
				} else if (!((($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/jpg")) 
						   && in_array(strtolower($extension), $allowedExts))) {
				//check the file's extension and type (image/jpeg or image/jpg)
					echo "<p>Please upload only .jpg files!</p>";
					include('addProjectForm.php');
					exit();
				} 
				//if all of the above validations suceed
				else {
					//check duplicates
					duplicates($dupfields);
				
					//move the uploaded file from the temporary location to the target path
					move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);
					
					// Insert the project into Projects database table.
					$query = "INSERT INTO Projects(Name, Caption, Cover_URL, PCF) VALUES('$name', '$caption', '$target_path', '$PCF');";
					$result = $mysqli->query($query);
					//if there is an error, exit
					if($mysqli->errno) {
						print($mysqli->error);
						include('addProjectForm.php');
						exit();	
					}
					//else print a succeed message and show the form to add another project.
					echo "<p>Successfully added a project</p>";
					include('addProjectForm.php');
					exit();	
				}
			}
			
			?>
		</div>
	</div>
</div>
</body>
</html>
