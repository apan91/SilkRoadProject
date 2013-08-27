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
			<h1>Register</h1>
			<?php
			
			//create an array of fields to check
			$fields = array('username', 'password', 'name', 'emailaddress');
			function filledin($fields) {
				//create an empty array of invalid inputs
				$invalid = array(); 
				//travers the fields array
					//if anything's not set of empty, insert to invalid array
				foreach($fields as $input) { 
					if(!(isset($_POST[$input]) && !empty($_POST[$input]))) { 
						$invalid[] = $input; 
					} 
				} 
				//if there is anything in invalid array
					//display all the items in invalid array and tell the user to fill in those parts
					//show the register form again
				if(count($invalid) > 0) { 
					echo "<p>You must fill in: ";
					echo implode($invalid, ", ");
					echo "</p>";
					include('registerForm.php');
					die();
				} 
			}
			
			$dupfields = array('username', 'emailaddress');
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
					$check = $_POST[$input];
					if($input == 'username')
						$dup = $mysqli->query("SELECT * FROM Users WHERE Username = '$check'");
					else
						$dup = $mysqli->query("SELECT * FROM Users WHERE Email = '$check'");
					//if there's any error, display the register form again with an error message
					if($mysqli->errno) {
						print($mysqli->error);
						include('registerForm.php');
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
					include('registerForm.php');
					die();
				} 
			}
			
			
			//if submitted the form
			if(isset($_POST['submit']) && !empty($_POST['submit'])) {
				//check if the user has filled in all the fields
				filledin($fields);
				//check if all the fields have valid formats again
				$invalid = array();
				if(!(preg_match('/^[A-Za-z]{1}[A-Za-z0-9\-\_]{5,14}$/', $_POST['username'])))
					$invalid[] = 'username';
				if(strlen($_POST['password']) < 6 || strlen($_POST['password']) > 16)
					$invalid[] = 'password';
				if(!(preg_match('/^[A-Za-z0-9\s\-\_]+$/', $_POST['name'])))
					$invalid[] = 'name';
				if(!(preg_match('/^[A-Za-z0-9\-\_]+@[A-Za-z0-9\-\_]+(\.[A-Za-z0-9\-\_]+)+$/', $_POST['emailaddress'])))
					$invalid[] = 'emailaddress';
				if(count($invalid) > 0) { 
					echo "<p>Invalid format of: ";
					echo implode($invalid, ", ");
					echo "</p>";
					include('registerForm.php');
					die();
				}
				
				//check duplicates for: username and emailaddress
				duplicates($dupfields);
				
				$username = $_POST['username'];
				$email = $_POST['emailaddress'];
			
				//read all the fields (hash the password)
				$hashpwd = hash('sha256', $_POST['password']);
				$name = $_POST['name'];
				//insert a new user information into the Users table.
				$result = $mysqli->query("INSERT INTO Users(Username, Hashpwd, Name, Email) VALUES('$username', '$hashpwd', '$name', '$email');");
				//if there's any error, display the register form again with an error message
				if($mysqli->errno) {
					print($mysqli->error);
					include('registerForm.php');
					exit();	
				}
				$mysqli->close();
				//else, show that the user has succesfully registered.
				//show them a link to login.
				echo "<p>successfully registered! Now login to use the members page.</p>";
				echo "<h3><a href='Login.php'>Click here to Log in</a></h3>";
			}
			else {
				include('registerForm.php');
			}
			?>
		</div>
	</div>
</div>
</body>
</html>
