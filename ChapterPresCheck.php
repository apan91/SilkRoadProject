<?php
if(!(isset($_SESSION['logged']) && !empty($_SESSION['logged']))) {
	echo "<h1>Please Login First</h1>";
	include('loginForm.php');
	exit();
}
//retrieve the userid from the logged session
$userid = $_SESSION['logged'];
$userquery = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
//if there's an error, display the error message and exit
if($mysqli->errno) {
	print($mysqli->error);
	exit();	
}
$user = $userquery->fetch_assoc();
$userperm = $user['Permission'];
if($userperm < 3) {
	print("<h1>No Permission</h1>");
	print("<h3><a href='EditProfile.php'>Click here to request permission change.</a></h3>");
	print("<form action='Logout.php' method='get'>");                
	print("<div><input type='submit' name='logout' value='Log Out' /></div>");
	print("</form>");
	exit();
}
?>