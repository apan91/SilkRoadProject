<?php
	require('includes/header.php');
	$errormsg = "";
	$msg = "";
			$fields = array('username', 'password');
			function filledin($fields) {
				$invalid = array(); 
				foreach($fields as $input) { 
					if(!(isset($_POST[$input]) && !empty($_POST[$input]))) { 
						$invalid[] = $input; 
					} 
				} 
				//if there were errors 
				if(count($invalid) > 0) { 
					$errormsg = "<p>You must fill in: ".implode($invalid, ", ")."</p>";
				} 
			}
			//if there is no one logged in yet
			if(!(isset($_SESSION['logged']) &&!empty($_SESSION['logged']))) {
				//check if the user has submitted a form
				if(isset($_POST['submit'])) {
					//check if all fields are filled in (username and password)
					filledin($fields);

					$username = $_POST['username'];
					$hashpwd = hash('sha256', $_POST['password']);
					//read from the database using the information given by the user
					$result = $mysqli->query("SELECT User_ID, Name FROM Users WHERE Username = '$username' AND Hashpwd = '$hashpwd';");
					//if there's any error, show the error message and login form again
					if($mysqli->errno) {
						$errormsg = $mysqli->error;
					}
					//if there is no rows the fetch, tell the user that there is no matching user information and to login again
					if(!($user = $result->fetch_row())) {
						$errormsg = "<p>No matching user. Please try again!</p>";
					}
					
					//if there is a row to fetch, display a welcome message
					$msg = "<p>Hello $user[1], you are successfully logged in.</p>";
					//add the user's id into logged session
					$_SESSION['logged'] = $user[0];
						//also show a link to go to the members page and a button to logout
					$msg .= "<h3><a href='Members.php'>Click here to go to Members lounge.</a></h3>";
				}
			}
			//else, tell the user that you are already logged in.
				//also show a link to go to the members page and a button to logout
			else {
				$msg = "you are already logged in!";
				$msg .= "<h3><a href='Members.php'>Click here to go to Members lounge.</a></h3>";
			}
	
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
			<h1>Login</h1>
			<?php
			
			if($errormsg != "") {
				print($errormsg);
				include('loginForm.php');
				exit();
			}
			if($msg != "") {
				print($msg);
				print("<form action='Logout.php' method='get'>");                
				print("<div><input type='submit' name='logout' value='Log Out' /></div>");
				print("</form>");
			}
			else {
				include('loginForm.php');
			}
			
			
			?>
			
		</div>
	</div>
</div>
</body>
</html>
