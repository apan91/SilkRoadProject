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
				
				printf("<h1>Add A Photo</h1><br/>");
				
				//check if submitted the form or not
				if(!(isset($_POST['button']) && !empty($_POST['button']))) {			
					include('addPhotoForm.php');
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
					$caption = mysql_real_escape_string($_POST['caption']);
					$date_taken = mysql_real_escape_string($_POST['date']);
									$check = datecheck($date_taken); //validate enddate
				if($check != "") {
					print($check);
					include('addPhotoForm.php');
					exit();
				}
					if(trim($caption) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $caption)) {
						echo "<p>Please provide a valid caption!</p>";
						include('addPhotoForm.php');
					} else if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
						echo "<p>Please provide a valid name!</p>";
						include('addPhotoForm.php');
					} else if (!((($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/jpg")) 
							   && in_array(strtolower($extension), $allowedExts))) {
						echo "<p>Please upload only .jpg files!</p>";
						include('addPhotoForm.php');
					} else if ($caption != '' && move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				
						$thumb_path = "thumbs/" .  $date->getTimestamp() . "_" . $image_name;
						make_thumb($target_path, $thumb_path, 300, 200);

						$activityid = mysql_real_escape_string($_POST['activity']);
						$countryid = mysql_real_escape_string($_POST['country']);
						$chapterid = mysql_real_escape_string($_POST['chapter']);

						// database sql statements
						$query = "INSERT INTO Photos VALUES(NULL, '$name', '$caption', '$target_path', '$thumb_path', '$date_taken');";
						$query .= "INSERT INTO Countries_IN VALUES('$countryid', (SELECT Photo_ID FROM Photos WHERE Url = '$target_path'));";
						$query .= "INSERT INTO Activities_IN VALUES('$activityid', (SELECT Photo_ID FROM Photos WHERE Url = '$target_path'));";
						$query .= "INSERT INTO Chapters_IN VALUES('$chapterid', (SELECT Photo_ID FROM Photos WHERE Url = '$target_path'));";

						
						multi_query($query);
						echo "<p>Successfully added a new Photo</p>";
						include('addPhotoForm.php');
					} else{
						echo "<p>There was an error uploading the file, please try again!</p>";
						include('addPhotoForm.php');
					}
				}	
			?>
		</div>
	</div>
</div>
</body>
</html>
