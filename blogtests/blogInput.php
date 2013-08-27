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

<?php
require("include/functions.php");
	
displayBlogInput();
?>


</body>
</html>