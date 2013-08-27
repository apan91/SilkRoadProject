<DOCTYPE html>
<html>
<head>
<title>Blog Page</title>
<link href="style.css" style="text/css" rel="stylesheet">
</head>
<body>
<?php
require("include/functions.php");
$sortedBlogList=getSortedBlog("index");
displayBlogSummary(0,2,$sortedBlogList);


?>




</body>
</html>