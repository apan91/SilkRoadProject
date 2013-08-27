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
			<h1>Message</h1>
			<?php 
			//create an array of fields to check
			$fields = array('sendTo', 'title', 'msg');
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
					include('messageForm.php');
					die();
				} 
			}
			
			if(isset($_POST['submit']) && !empty($_POST['submit'])) {
				//check if the user has filled in all the fields
				filledin($fields);
				//check if all the fields have valid formats again
				//read all the fields
				
				$msg = $_POST['msg'];
				$title = $_POST['title'];
				

				if(trim($title) == '') {
					echo "<p>Please provide a valid title!</p>";
					include('messageForm.php');
					exit();
				} else if(trim($msg) == '') {
				//validate the message posted again, to make sure there is something as a caption.
					echo "<p>Please provide a valid message!</p>";
					include('messageForm.php');
					exit();
				} 
				
				

				$sendTo = "";
				
				//retrieve all information from the Users table
				$perm = $_POST['sendTo'];
				$user = $mysqli->query("SELECT * FROM Users WHERE Permission = '$perm'");
				//if there is any error, display an error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//fetch all the user information
				//USe comma to send to multiple people
				while($result = $user->fetch_assoc()) {
					$sendTo .= $result['Email'].", ";
				}

				
				
				//get the user id from the logged session
				$userid = $_SESSION['logged'];
				//retrieve all the information from Users table where the user id matches
				$user = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//fetch a row and display all the information
				$result = $user->fetch_assoc();
				$username = $result['Username'];
				$name = $result['Name'];
				$emailAddress = $result['Email'];
								
				$subject = "Silkroad Message from ".$username.": ".$title;

				$emailMessage = "Form details below.\n\n";
		 
				function clean($string) {
				  $bad = array("content-type", "bcc:", "to:", "cc:", "href");
				  return str_replace($bad, "", $string);
				}
			
				$emailMessage .= "Username: ".clean($username)."\n";
				$emailMessage .= "Name: ".clean($name)."\n";
				$emailMessage .= "Email: ".clean($emailAddress)."\n";
				$emailMessage .= "Message: ".clean($msg)."\n";
				
				$header = 'From: '.$emailAddress."\r\n".'Reply-To: '.$emailAddress."\r\n".'X-Mailer: PHP/'.phpversion();
				@mail($sendTo, $subject, $emailMessage, $header);  
				echo "Successfully sent a message!";
			}
			include('messageForm.php');
			
			?>
			</div>
    </div>
</div>
</body>
</html>
