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
				printf("<h1>Add New Chapter</h1><br/>");
				
				//check if submitted the form or not
				if(!(isset($_POST['button']) && !empty($_POST['button']))) {
					include('addChapterForm.php');
					exit();
				} 
				
				// for getting current timestamp
				$date = new DateTime();
				$target_path = "uploads/";
				
				if(!isset($_FILES['uploadedfile'])) {
					echo "<p>There was an error uploading the file, please try again!</p>";
					include('addPhotoForm.php');
				} else {
					$image_name = basename( $_FILES['uploadedfile']['name']);
				
					// uploads/filename.extension
					$target_path =  $target_path . $date->getTimestamp() . "_" . $image_name;
					
					$allowedExts = array("jpeg", "jpg");
					$extension = end(explode(".", $_FILES["uploadedfile"]["name"]));
					
				
					$name = mysql_real_escape_string($_POST['name']);
					//validate the name posted again, to make sure only texts, numbers, -, _ or spaces are in name.
					if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
						echo "<p>Please provide a valid name!</p>";
						include('addChapterForm.php');
						exit();
					} else if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)){
						
						$thumb_path = "thumbs/" .  $date->getTimestamp() . "_" . $image_name;
						make_thumb($target_path, $thumb_path, 300, 200);
						
						// Insert the cause into Chapter database table.
						$query = "INSERT INTO Chapters VALUES(NULL,'$name','$target_path','$thumb_path');";
						
						$result = $mysqli->query($query);
						//if there is an error, exit
						if($mysqli->errno) {
							print($mysqli->error);
							include('addChapterForm.php');
							exit();	
						}
						//else print a succeed message and show the form to add another cause.
						echo "<p>Successfully added an Chapter</p>";
						include('addChapterForm.php');
						exit();	
					} else {
						echo "<p>There was an error uploading the file, please try again!</p>";
						include('addChapterForm.php');
					}
				}
			?>
		</div>
	</div>
</div>
</body>
</html>