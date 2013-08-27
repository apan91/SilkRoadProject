<?php
	require('includes/header.php');
	$msg = "";
	$errormsg = "";
	if(isset($_GET['logout']) && !empty($_GET['logout'])) {
		$msg = "<h1>Good bye!</h1>";
		unset($_SESSION['logged']);
		session_destroy();
	}
	if(!(isset($_SESSION['logged']) && !empty($_SESSION['logged']))) {
		$errormsg = "<h1>Please Login first</h1>";
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
			<?php
			if ($msg != "") {
				print($msg);
				include('loginForm.php');
			}
			else if ($errormsg != ""){
				print("<h1>Please Login first</h1>");
				include('loginForm.php');
			}
			else {
				print("<h1>Do you really wish to Log out?</h1>");
				print("<form action='Logout.php' method='get'>");                
				print("<div><input type='submit' name='logout' value='Log Out' /></div>");
				print("</form>");
			}
			?>
			
		</div>
	</div>
</div>
</body>
</html>
