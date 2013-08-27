<?php
	if (!isset($_SESSION))
		session_start();
?>
<?php
	require('includes/header.php');
?>
<DOCTYPE html>
<html>
<head>
<title>Blog Page</title>
<link href="blogstyle.css" style="text/css" rel="stylesheet">
</head>
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
require_once('includes/blogFunctions.php');
displayAllTags();
?>

		</div>
	</div>
</div>


</body>
</html>