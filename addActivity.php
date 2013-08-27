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
				printf("<h1>Add New Activity</h1><br/>");
				
				//check if submitted the form or not
				if(!(isset($_POST['button']) && !empty($_POST['button']))) {
					include('addActivityForm.php');
					exit();
				} 
				
				$name = mysql_real_escape_string($_POST['name']);
				//validate the name posted again, to make sure only texts, numbers, -, _ or spaces are in name.
				if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>Please provide a valid name!</p>";
					include('addActivityForm.php');
					exit();
				} else {
					// Insert the cause into Activity database table.
					$query = "INSERT INTO Activities VALUES(NULL,'$name');";
					
					$result = $mysqli->query($query);
					//if there is an error, exit
					if($mysqli->errno) {
						print($mysqli->error);
						include('addActivityForm.php');
						exit();	
					}
					//else print a succeed message and show the form to add another cause.
					echo "<p>Successfully added an Activity</p>";
					include('addActivityForm.php');
					exit();	
				}
			?>
		</div>
	</div>
</div>
</body>
</html>
