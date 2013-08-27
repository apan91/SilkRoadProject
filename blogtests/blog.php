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
print("<div class=\"mainContent\">");
if (isset($_SESSION['logged'])){
	$userID=$_SESSION['logged'];
}
else{
	$userID=FALSE;
}
if (isset($_GET["type"])==true){
	$whatToDisplay=$_GET["type"];
	if ($whatToDisplay=="blog"){
		$blogID=$_GET["blogID"];
		require("include/functions.php");
		displayBlog($blogID);
		if ($userID!=FALSE){
			displayCommentInput($blogID);
		}
		else{
			echo "Log in to make comments";
		}
		}
	if ($whatToDisplay=="blogsummary"){
		$selectedPageNum=$_GET["selectedpage"]-1;
		$numPerPage=$_GET["numperpage"];
		require("include/functions.php");
		$sortedBlogList=getSortedBlog("date");
		displayBlogSummary($selectedPageNum*$numPerPage,($selectedPageNum*$numPerPage+$numPerPage),$sortedBlogList);
		DisplayBlogSummaryPagination($numPerPage);
	}
	if ($whatToDisplay=="blogsbytag"){
		$tagID=$_GET["tagID"];
		require("include/functions.php");
		$blogList=getBlogsByTag($tagID);
		displayBlogSummary(0,100,$blogList);
	}	
	if ($whatToDisplay=="blogsbyauthor"){
		$authorID=$_GET["authorID"];
		require("include/functions.php");
		$authorList=getBlogsByAuthor($authorID);
		displayBlogSummary(0,100,$authorList);
	}	
}
else{

require("include/functions.php");
$sortedBlogList=getSortedBlog("date");
displayBlogSummary(0,5,$sortedBlogList);
DisplayBlogSummaryPagination(5);


}
print("</div>");
print("<div class=\"sideContent\">");

displayTags(getTopTags());
displayAuthors(getTopAuthors());
print("</div>");
?>




</body>
</html>