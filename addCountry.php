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
				printf("<h1>Add New Country</h1><br/>");
				
				//check if submitted the form or not
				if(!(isset($_POST['button']) && !empty($_POST['button']))) {
					include('addCountryForm.php');
					exit();
				} 
				
				$name = mysql_real_escape_string($_POST['name']);
				//validate the name posted again, to make sure only texts, numbers, -, _ or spaces are in name.
				if(trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>Please provide a valid name!</p>";
					include('addCountryForm.php');
					exit();
				} else {
					$regionid = mysql_real_escape_string($_POST['region']);
					
					$query = "INSERT INTO Countries VALUES(NULL,'$name');";
					$query .= "INSERT INTO Regions_IN VALUES('$regionid', (SELECT Country_ID FROM Countries WHERE Name = '$name'));";
					
					multi_query($query);
					//if there is an error, exit
					if($mysqli->errno) {
						print($mysqli->error);
						include('addCountryForm.php');
						exit();	
					}
					//else print a succeed message and show the form to add another cause.
					echo "<p>Successfully added an Country</p>";
					include('addCountryForm.php');
					exit();	
				}
			?>
		</div>
	</div>
</div>
</body>
</html>
