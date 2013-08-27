<?php
	require('includes/header.php');
	if(!(isset($_SESSION['logged']) && !empty($_SESSION['logged'])))
		header('location:Login.php');

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
			<h1>Edit My Profile</h1>
			<?php
			
			//create an array of fields listing the inputs to be changed
			$fields = array('newpassword', 'name', 'emailaddress', 'permission');
			
			//new function to return an array of fields with filled in inputs
			function validfilledin($fields) {
				//create an empty array called valid
				$valid = array(); 
				//traverse fields
				foreach($fields as $input) { 
					//if its input is set and not empty insert into valid array
					if(isset($_POST[$input]) && !empty($_POST[$input])) { 
						$valid[] = $input;
					}
				} 
				//if there is nothing in valid, tell the user to fill in at least one thing to change
					//include the edit profile form and exit
				if(count($valid) < 1) { 
					echo "<p>You must fill in at least one thing to change";
					include('editProfileForm.php');
					die();
				} 
				//else if there is something in the valid array, return it.
				return $valid;
			}

			//check if the user has submitted the form
			if(isset($_POST['submit']) && !empty($_POST['submit'])) {
				//check if the user typed the old password first
				if(isset($_POST['oldpassword']) && !empty($_POST['oldpassword'])) { 
					//check if the old password matches the user's password
					$userid = $_SESSION['logged'];
					//by retrieving the hashed password of the logged-in user
					$result = $mysqli->query("SELECT Hashpwd FROM Users WHERE User_ID = '$userid';");
					if($mysqli->errno) {
						print($mysqli->error);
						include('editProfileForm.php');
						exit();	
					}
					$old = $result->fetch_row();
					//compare the two values, if it is wrong, tell the user that the password is incorrect.
					if (hash('sha256', $_POST['oldpassword']) != $old[0]) {
						print("<p>Wrong password</p>");
						include('editProfileForm.php');
						exit();
					}
					
					//check if the user has filled in at least one field to change
					$valid = validfilledin($fields);
					//check if the fields have valid formats again.
					//valid($fields);
					
					//check each field and see if it's in the valid array
						//if it is in valid array, update that user's information for that field
					if(in_array('newpassword', $valid)) {
						if(strlen($_POST['newpassword']) < 6 || strlen($_POST['newpassword']) > 16) {
							print("<p>Invalid format of password.</p>");
							include('editProfileForm.php');
							exit();
						}							
						$newpwd = hash('sha256', $_POST['newpassword']);
						$result = $mysqli->query("UPDATE Users SET Hashpwd = '$newpwd' WHERE User_ID = '$userid';");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully updated password</p>");
					}
					if(in_array('name', $valid)) {
						$name = $_POST['name'];
						if(!(preg_match('/^[A-Za-z0-9\s\-\_]+$/', $_POST['name']))) {
							print("<p>Invalid format of password.</p>");
							include('editProfileForm.php');
							exit();
						}
						$result = $mysqli->query("UPDATE Users SET Name = '$name' WHERE User_ID = '$userid';");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully updated name</p>");
					}
					if(in_array('emailaddress', $valid)) {
						$email = $_POST['emailaddress'];
						if(!(preg_match('/^[A-Za-z0-9\-\_]+@[A-Za-z0-9\-\_]+(\.[A-Za-z0-9\-\_]+)+$/', $_POST['emailaddress']))){
							print("<p>Invalid format of password.</p>");
							include('editProfileForm.php');
							exit();
						}
						$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
						if ($mysqli->errno) {
							print($mysqli->error);
							exit();
						}
						$dup = $mysqli->query("SELECT * FROM Users WHERE Email = '$email'");
						//if there's any error, display the register form again with an error message
						if($mysqli->errno) {
							print($mysqli->error);
							include('editProfileForm.php');
							exit();	
						}
						if($dup->num_rows > 0){
							print("<p>Duplicate email address.</p>");
							include('editProfileForm.php');
							exit();
						}
						$result = $mysqli->query("UPDATE Users SET Email = '$email' WHERE User_ID = '$userid';");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully updated email address</p>");
					}
					if(in_array('permission', $valid)) {
						$perm = $_POST['permission'];
						$result = $mysqli->query("INSERT INTO Requests(User_ID, Requested) VALUES('$userid','$perm');");
						if($mysqli->errno)
							print($mysqli->error);
						else
							print("<p>Successfully requested a permission change.</p>");
					}

					$mysqli->close();
					
				}
				else {
					echo "<p>you must enter your old password</p>";
					include('editProfileForm.php');
				}
			}
			else {
				include('editProfileForm.php');
			}
			?>
		</div>
	</div>
</div>
</body>
</html>
