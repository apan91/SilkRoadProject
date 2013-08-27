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
			<h1>Send us a Feedback!</h1>
			<?php 
			//create an array of fields to check
			$fields = array('name', 'emailaddress', 'comments');
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
					include('contactForm.php');
					die();
				} 
			}
			
			if(isset($_POST['submit']) && !empty($_POST['submit'])) {
				//check if the user has filled in all the fields
				filledin($fields);
				//check if all the fields have valid formats again
				//valid($fields);
				//read all the fields
				$name = $_POST['name']; 
				$emailAddress = $_POST['emailaddress']; 
				$comments = $_POST['comments'];

				$sendTo = "jylim03@gmail.com";
				$subject = "Silkroad Feedback/Comments";

				$emailMessage = "Form details below.\n\n";
		 
				function clean($string) {
				  $bad = array("content-type", "bcc:", "to:", "cc:", "href");
				  return str_replace($bad, "", $string);
				}
				
				//if logged in, put user info too?
				if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])) {
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
					$emailMessage .= "Username: ".clean($username)."\n";
					$subject .= " by ".clean($username)."\n";
				}
				$emailMessage .= "Name: ".clean($name)."\n";
				$emailMessage .= "Email: ".clean($emailAddress)."\n";
				$emailMessage .= "Comments: ".clean($comments)."\n";
				
				$header = 'From: '.$emailAddress."\r\n".'Reply-To: '.$emailAddress."\r\n".'X-Mailer: PHP/'.phpversion();
				@mail($sendTo, $subject, $emailMessage, $header);  
				echo "Thank you for your feedback!";
			}
			include('contactForm.php');
			
			?>
			<h1>External Links</h1>
			<a href="https://www.facebook.com/CornellSilkRoad?fref=ts" target="_blank" title="Facebook"><img src='includes/facebook-icon.png' width='30px' height='30px' /></a>
<a href="https://twitter.com/silkroadinc" target="_blank" title="Twitter"><img src='includes/twitter-icon.png' width='30px' height='30px' /></a>
			</div>
    </div>
</div>
</body>
</html>
