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
				include('memberCheck.php');
			?>
			<h1>Upload a File</h1>
			<?php 
			
			$dupfields = array('name', 'filename');
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
							$dup = $mysqli->query("SELECT * FROM Files WHERE Name = '".$_POST['name']."'");
						else
							$dup = $mysqli->query("SELECT * FROM Files WHERE URL = 'downloads/".$_FILES['uploadedfile']['name']."'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('uploadFileForm.php');
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
						include('uploadFileForm.php');
						die();
					} 
				}
			
			if(!(isset($_POST['submit']) && !empty($_POST['submit']))) {
				include('uploadFileForm.php');
				exit();
			}
			if(!isset($_FILES['uploadedfile']) || empty($_FILES['uploadedfile'])) {
				echo "<p>Please select a file to upload</p>";
				include('uploadFileForm.php');
				exit();
			} else {
				$file_name = basename( $_FILES['uploadedfile']['name']);
				$target_path =  "downloads/".$file_name;	
				$extension = end(explode(".", $_FILES["uploadedfile"]["name"]));
				$name = mysql_real_escape_string($_POST['name']);
				$caption = mysql_real_escape_string($_POST['caption']);
				
				if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>Please provide a valid name!</p>";
					include('uploadFileForm.php');
					exit();
				} else if(trim($caption) == '') {
					echo "<p>Please provide a valid caption!</p>";
					include('uploadFileForm.php');
					exit();
				} 
				else {
					duplicates($dupfields);
				
					move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);
					$userid = $_SESSION['logged'];
					
					// database sql statements
					$query = "INSERT INTO Files(Name, Type, Caption, User_ID, URL) VALUES('$name', '$extension', '$caption', '$userid', '$target_path');";
					$result = $mysqli->query($query);
					if($mysqli->errno) {
						print($mysqli->error);
						include('uploadFileForm.php');
						exit();	
					}
					
					echo "<p>Successfully uploaded the file</p>";
					include('uploadFileForm.php');
					exit();	
				}
			}
			
			?>
		</div>
	</div>
</div>
</body>
</html>
