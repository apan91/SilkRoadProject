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
			<h1>Find Password</h1>
			<?php
			
			function sendemail($sendTo, $user, $newPassword) {
				function clean($string) {
				  $bad = array("content-type", "bcc:", "to:", "cc:", "href");
				  return str_replace($bad, "", $string);
				}
				 
				$emailMessage = "Hello ".$user."! Your new password is: ".clean($newPassword)."\n";
				$emailAddress = "jylim03@gmail.com"; //change to admin's email address
				 
				$subject = "New Password from Silkroad";
				$header = 'From: '.$emailAddress."\r\n".'Reply-To: '.$emailAddress."\r\n".'X-Mailer: PHP/'.phpversion();
				@mail($sendTo, $subject, $emailMessage, $header); 
			}	
			
			$fields = array('username', 'emailAddress');
			function filledin($fields) {
				$invalid = array(); 
				foreach($fields as $input) { 
					if(!(isset($_POST[$input]) && !empty($_POST[$input]))) { 
						$invalid[] = $input; 
					} 
				} 
				//if there were errors 
				if(count($invalid) > 0) { 
					echo "<p>You must fill in: ";
					echo implode($invalid, ", ");
					echo "</p>";
					include('findPasswordForm.php');
					die();
				} 
			}
			
			function createRandPassword() {
				$chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*";
				$list = array();
				$length = strlen($chars) - 1;
				for ($x = 0; $x < 8; $x++) {
					$index = rand(0, $length);
					$list[] = $chars[$index];
				}
				return implode($list); 
			}

			//check if the user has submitted a form
			if(isset($_POST['submit'])) {
				//check if all fields are filled in (username and password)
				filledin($fields);
				//check if all the inputs are valid formats again
				//valid($fields);
				$username = $_POST['username'];
				$email = $_POST['emailAddress'];
				
				//read from the database using the information given by the user
				$result = $mysqli->query("SELECT * FROM Users WHERE Username = '$username' AND Email = '$email';");
				//if there's any error, show the error message and login form again
				if($mysqli->errno) {
					print($mysqli->error);
					include('findPasswordForm.php');
					exit();	
				}
				//if there is no rows the fetch, tell the user that there is no matching user information and to login again
				if(!($user = $result->fetch_row())) {
					echo "<p>No matching user. Please try again!</p>";
					include('findPasswordForm.php');
					exit();
				}
				
				//generate a new random password
				$newPassword = createRandPassword();
				$hashpwd = hash('sha256', $newPassword);
				//update the information
				$result = $mysqli->query("UPDATE Users SET Hashpwd = '$hashpwd' WHERE Username = '$username' AND Email = '$email';");
				//if there's any error, show the error message and login form again
				if($mysqli->errno) {
					print($mysqli->error);
					include('findPasswordForm.php');
					exit();	
				}
				sendemail($email, $username, $newPassword);
				
				echo "<p>We emailed you a new password. It may take a few minutes.</p>";
				echo "<p><a href='Login.php'>Click here to go back to the login page.</a></p>";
			}
			else {
				include('findPasswordForm.php');
			}
			?>
			
		</div>
	</div>
</div>
</body>
</html>
