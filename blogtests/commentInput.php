<?php
	if (!isset($_SESSION))
		session_start();
?>

<DOCTYPE html>
<html>
<head>
<title>Blog Page</title>
<link href="blogstyle.css" style="text/css" rel="stylesheet">
</head>
<body>

<form action="commentInput.php" method="post">
<input type="hidden" name="userID" value="2">
Comment: <input type="text" name="post">
<input type="submit" name="submitBlog">
</form>
<?php
if (isset($_POST["submitBlog"])==true){
	
	$postID=1;
	$post=$_POST["post"];
	//change to session
	//$userID=$_SESSION['logged'];
	$userID=2;
	$date=date('Y/m/d H:i:s');
	require("include/functions.php");
	insertComments($postID,$userID,$post,$date);
}

?>


</body>
</html>