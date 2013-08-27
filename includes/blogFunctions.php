<?php
##all purpose functions

//requires the post index number, and outputs the result in a associative array.
function getSortedBlog($sortMethod){
	$blogList=array();
	
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	 //depending on what sorting order selected, it will return a list sorted by that order
	if ($sortMethod=="index"){
		$query=sprintf("SELECT * FROM Posts ORDER BY Post_ID");
		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		}
	if ($sortMethod=="date"){
		$query=sprintf("SELECT * FROM Posts ORDER BY Date DESC");
		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		}
	//fetches row in the array and puts them in the associative arrayList
	while($row = mysqli_fetch_array($result)){
		array_push($blogList,$row);
	}
	mysqli_close($mysqli);
	return $blogList;
	

}
//returns a associative list for a specific user based on their ID
function getUser($userID){
	$userList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT * FROM Users WHERE User_ID=".$userID);

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
				$userList=$row;

			}
			mysqli_close($mysqli);
		  return $userList;
  

}
function getTags($postID){
	$tagList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT * FROM Tagged NATURAL JOIN Tags WHERE Post_ID=".$postID);

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
				array_push($tagList,$row);
			}
			mysqli_close($mysqli);
		  return $tagList;
  
}
//given PostID, fetches all the comments for that post ordered by dateTime
function getComments($postID){
	
	$commentsList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT * FROM Comments WHERE Post_ID=".$postID." ORDER BY Date DESC");

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
				array_push($commentsList,$row);
			}
			mysqli_close($mysqli);
		  return $commentsList;

}
//ouputs the link corresponding to the list of tags.
function displayTagsNormal($tagList){
	$tempList=array();
	print("<div id=\"blogtags\">");
	print("Tags: ");
	foreach ($tagList as $row){
//		array_push($tempList,"<a href=Blog.php?type=blogsbytag&tagID=".$row[0]."><input type=\"submit\" class=\"btn btn-mini\" name=\"likeSubmit\" value='$row[2]'></a>");
		array_push($tempList,"<a href=Blog.php?type=blogsbytag&tagID=".$row[0].">".$row[2]."</a>");
	}
	print(implode(", ",$tempList));
	print("</div>");
}





##blog main functions for the page
function displayBlogMain(){
	if (isset($_SESSION['logged'])){
		$userID=$_SESSION['logged'];
	}
	else{
		$userID=FALSE;
	}
	print("<div class='container-fluid'><div class='row-fluid'>");
	print ("<div class=\"span9\">");
	if (isset($_GET["type"])==true){
		$whatToDisplay=$_GET["type"];
		if ($whatToDisplay=="blog"){
			$blogID=$_GET["blogID"];
			displayBlog($blogID);
			if ($userID!=FALSE){
				displayCommentInput($blogID);
			}
			else{
				echo "<b><a href=Login.php>Log in to make comments<a></b>";
			}
		}
		if ($whatToDisplay=="blogsummary"){
			$selectedPageNum=$_GET["selectedpage"]-1;
			$numPerPage=$_GET["numperpage"];
			DisplayBlogSummaryPagination($numPerPage);
			$sortedBlogList=getSortedBlog("date");
			displayBlogSummary($selectedPageNum*$numPerPage,($selectedPageNum*$numPerPage+$numPerPage),$sortedBlogList);
		}
		if ($whatToDisplay=="blogsbytag"){
			$tagID=$_GET["tagID"];
			$blogList=getBlogsByTag($tagID);
			displayBlogSummary(0,100,$blogList);
		}	
		if ($whatToDisplay=="blogsbyauthor"){
			$authorID=$_GET["authorID"];
			$authorList=getBlogsByAuthor($authorID);
			displayBlogSummary(0,100,$authorList);
		}	
	
	}
	else{
		DisplayBlogSummaryPagination(5);
		$sortedBlogList=getSortedBlog("date");
		displayBlogSummary(0,5,$sortedBlogList);
	}
	print ("</div>");
	print("<div class=\"span2\">");
	displayAddEditBlog();
	displayTopTags(getTopTags());
	displayAuthors(getTopAuthors());
	print("</div>");
	print("</div></div>");
}
//displays options to add/edit blogs for chapter presidents and admins
function displayAddEditBlog(){
	
	if (isset($_SESSION['logged'])){
		//display add/edit option up top
		$userID=$_SESSION['logged'];
		$user=getUser($userID);
		$userPermission=$user["Permission"];

		if ($userPermission>2){
				echo "<a href='addBlog.php'><span class='btn btn-primary'>Write Post</span></a>&nbsp;";
				echo "<a href='editBlog.php'><span class='btn btn-primary'>Edit/Delete</span></a>";
		}
	}

}


##blog summary functions

//outputs the blog summary 
function displayBlogSummary($beginningIndex,$endingIndex,$sortedList){
	//makes sure it doesnt not go out of bound
	if ($endingIndex>(sizeof($sortedList)-1)){
		$endingIndex=(sizeof($sortedList));
	}

	For ($count=$beginningIndex;$count<=$endingIndex-1;$count++){
		print("<div class=\"summaryDiv\">");

		if ($sortedList[$count]["Url"]!=Null){
			print("<div class=\"span8\">");
			DisplaySummaryTitle($sortedList[$count]["Title"],$sortedList[$count]["Post_ID"]);
			DisplaySummaryAuthor($sortedList[$count]["User_ID"]);
			DisplaySummaryDate($sortedList[$count]["Date"]);
			DisplaySummaryPost($sortedList[$count]["Post"]);
			DisplaySummaryTags($sortedList[$count]["Post_ID"]);
			DisplaySummaryNumLikes($sortedList[$count]["NumLiked"]);
			//print("<hr id=\"horizontalRuleBlog\">");
			print("</div>");

			print("<div class=\"span4\">");
			DisplaySummaryCoverPic($sortedList[$count]["Url"]);
			print("</div>");
		}
		else {
		
			print("<div class=\"span8\">");
			DisplaySummaryTitle($sortedList[$count]["Title"],$sortedList[$count]["Post_ID"]);
			DisplaySummaryAuthor($sortedList[$count]["User_ID"]);
			DisplaySummaryDate($sortedList[$count]["Date"]);
			DisplaySummaryPost($sortedList[$count]["Post"]);
			DisplaySummaryTags($sortedList[$count]["Post_ID"]);
			DisplaySummaryNumLikes($sortedList[$count]["NumLiked"]);
			//print("<hr id=\"horizontalRuleBlog\">");
			print("</div>");
			print("<div class=\"span4\">");
			print("</div>");
		}
		print("</div>");
		
	}

}
//displays the formated title of the summary
function DisplaySummaryTitle($title,$postID){
	print("<div id=\"blogSummaryTitle\" class=\"blogSummaryTitle\"><a href=\"Blog.php?type=blog&blogID=".$postID."\">".$title."</a></div>");
}
//displays the formated author of the summary
function DisplaySummaryAuthor($authorID){
	$author=getUser($authorID);
	print("<div class=\"blogSummeryAuthor\">");
	displayAuthorsSingle($author);
	print("</div>");
}
//implode all the tags, seperated by comma
function DisplaySummaryTags($postID){
	displayTagsNormal(getTags($postID));
}
//displays the formated paragraph of the blog summary limit to 100 ch1aracters)
function DisplaySummaryPost($post){
	$post=substr($post,0,100);
	print("<div class=\"blogSummeryPost\">".$post."...</div>");

}
//displays the formated cover photo of the summary
function DisplaySummaryCoverPic($url){
	print("<div class=\"blogSummeryCoverPic\"><img src=\"images/blog/thumbs/".basename($url)."\"></div>");
}
//displays the formated date of the summary
function DisplaySummaryDate($date){
	print("<div class=\"blogSummeryDate\"> (".$date.")</div>");
}
//displays the formated number of likes of the summary
function DisplaySummaryNumLikes($numLikes){
	print("<div class=\"blogSummeryNumLikes\">Total Likes: ".$numLikes."</div>");
}
//displays the link for the summary that will pass info via URL for the blog page to process (using GET)
function DisplaySummaryUrl($postID){
	print("<div class=\"blogSummeryUrl\"><a href=\"Blog.php?type=blog&blogID=".$postID."\">See the full blog</a></div>");
}

##section for displaying the full blog given args that was passed through the url (GET)
//given blog id, get the content of the blog
function getSingleBlog($postID){

	$post=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT * FROM Posts WHERE Post_ID=".$postID);

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
				$post=$row;
			}
			mysqli_close($mysqli);
			return $post;

}
//dipsplays the blog along with it's comments
function displayBlog($postID){
	if(isset($_SESSION['logged'])){
			$userID=$_SESSION['logged'];
		if (isset($_POST['likeSubmit']) && !empty($_POST['likeSubmit'])){
			processLike($postID,$userID);
		}
	}
	else{
		$userID=NULL;
	}


	$post=getSingleBlog($postID);
	$tagList=getTags($postID);

	$commentsList=getComments($postID);
	$authorID=$post["User_ID"];
	DisplayBlogCoverPic($post["Url"]);
	DisplayBlogTitle($post["Title"]);
	DisplayBlogAuthor($authorID);
	DisplayBlogDate($post["Date"]);
	DisplayBlogPost($post["Post"]);
	DisplayBlogTags($postID);
	DisplayBlogNumLikes($post["NumLiked"], $postID,$userID);
	
	//else{
		//print("<a href=Login.php><input type=\"submit\" class=\"btn btn-primary\" name=\"likeSubmit\" value=\"Like\"></a></br>");
//	}
	if (isset($_POST["submitComment"])==true){
		$postID=$_POST["postID"];
		$post=$_POST["post"];
		$date=date('Y/m/d H:i:s');
		insertComments($postID,$userID,$post,$date);
		}
	if (sizeof($commentsList)>0){
		displayComments($commentsList);
	}
}
//displays the comments
function displayComments($commentsList){
	print("<b>Comments Made By Members</b><br/>");
	if (isset($_SESSION['logged'])){
		if (isset($_POST["editComment"])){
			editComment($_POST["postID"],$_POST["userID"],$_POST["commentDate"],$_POST["post"]);
		}
		if (isset($_POST["deleteComment"])){
			deleteComment($_POST["postID"],$_POST["userID"],$_POST["commentDate"]);
		}
	}
	foreach ($commentsList as $row){
	$commenterName=getUser($row["User_ID"]);
	$date=$row["Date"];
	$post=$row["Post"];
	print("<div class='well well-small'>");
	//print("<hr id=\"horizontalRuleComment\">");
	print("<div id=\"commentAuthor\" class=\"DisplayCommentsUserName\">".$commenterName["Name"]." (".$commenterName['Username'].") on ".$date."</div>");
	//print("<div class=\"DisplayCommentsDate\">".$date."</div>");
	//if the user is logged in, display the edit and delete options
	if (isset($_SESSION['logged'])){
		if($row['User_ID'] == $_SESSION['logged']) {
			print("<div class=\"commentEdit input-append\">");
			print("<form action='' method=\"post\" >");
			print("<input name=\"post\" type=\"text\" value=\"".$post."\" class=\"something\" />");
			print("<input name=\"postID\" type=\"hidden\" value=\"".$row["Post_ID"]."\" class=\"something\" />");
			print("<input name=\"userID\" type=\"hidden\" value=\"".$row["User_ID"]."\" class=\"something\" />");
			print("<input name=\"commentDate\" type=\"hidden\" value=\"".$row["Date"]."\" class=\"something\" />");
			print("<input id=\"upload\" type=\"submit\" class=\"btn btn-primary\" name=\"editComment\" value=\"Change\" />");
			print("<input id=\"upload\" type=\"submit\" class=\"btn btn-primary\" name=\"deleteComment\" value=\"Delete\" />");
			print("</form>");
			print("</div>");
		}
		else
			print("<div class=\"DisplayCommentsPost\">".$post."</div>");
		
	}
	else
		print("<div class=\"DisplayCommentsPost\">".$post."</div>");

	print("</div>");
	}
}
//displays the formated title of the blog
function DisplayBlogTitle($title){
	print("<div id=\"blogTitle\" class=\"blogTitle\">".$title."</div>");

}
//displays the formated author of the blog
function DisplayBlogAuthor($authorID){
	$author=getUser($authorID);
	print("<div class=\"blogAuthor\"> ");
	displayAuthorsSingle($author);
	print("</div>");
}
//implode all the tags, seperated by comma
function DisplayBlogTags($postID){
	displayTagsNormal(getTags($postID));
}
//displays the formated paragraph of the blog
function DisplayBlogPost($post){
	print("<div class=\"blogPost\">".$post."</div>");

}
//displays the formated cover photo
function DisplayBlogCoverPic($url){
if ($url!=Null){
		$image=getimagesize($url);
		$imageWidth=$image[0];
		$imageHeight=$image[1];
		$ratio=$imageHeight/$imageWidth;
		if ($imageWidth>400){
			$imageWidth=400;
			$imageHeight=$imageWidth*$ratio;
		}
		if ($imageHeight>400){
			$imageHeight=400;
			$imageWidth=$imageHeight/$ratio;
		}
		print("<div class=\"blogCoverPic\"><img src='$url' width=\"".$imageWidth."px\"height=\"".$imageHeight."\"></div>");
	}
else{
	print("<div class=\"blogCoverPic\"><img src='$url'></div>");
}
}
//displays the formated date
function DisplayBlogDate($date){
	print("<div class=\"blogDate\">&nbspon ".$date."</div>");
}
//displays the formated number of likes
function DisplayBlogNumLikes($numLikes, $postID,$userID){
	print("<form action=\"".$_SERVER['REQUEST_URI']."\" method=\"Post\">");
	print("<div class=\"blogNumLikes\"><b>Total Likes: </b>".$numLikes."&nbsp;");
	if (isset($_SESSION['logged'])){
		displayLikeButton($postID,$userID);
	}
	
	print("</form></div>");
}
## pagination functions of blog
//display the appropriate # of links
function DisplayBlogSummaryPagination($numPerPage){
	$numPerPage=intval($numPerPage);
	$totalEntries=getTotalEntries();
	$final=$totalEntries[0]/$numPerPage;
	$final=intval($final);
	if ($final*$numPerPage-$totalEntries[0]==0){
		$final=$final-1;
	}
	$pageList=Array();
	for ($count=0;$count<=$final;$count++){
		//array_push($pageList,"<a href=\"Blog.php?type=blogsummary&selectedpage=".($count+1)."&numperpage=".$numPerPage."\">".($count+1)."</a>");
	array_push($pageList,"<li><a href=\"Blog.php?type=blogsummary&selectedpage=".($count+1)."&numperpage=".$numPerPage."\">".($count+1)."</a></li>");	
	}
	implode("&nbsp",$pageList);
	print("<div class='pagination'><ul>");
	print(implode("&nbsp",$pageList));
	print("</ul></div>");

}
//opens the database and counts how many blog posts exists
function getTotalEntries(){
$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT COUNT(Post_ID) FROM Posts");

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
			  	mysqli_close($mysqli);
				return $row;

			}

}
## Top tags function

//gets the top tags (5 or 10)
function getTopTags(){
	$tagList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT Tag_Name, Count(Tag_ID),Tag_ID FROM Tags NATURAL JOIN Tagged GROUP BY Tag_ID ORDER BY Count(Tag_ID) DESC LIMIT 10");

	$result=$mysqli->query($query);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	while($row = mysqli_fetch_array($result)){
		array_push($tagList,$row);
	}
	mysqli_close($mysqli);
	return $tagList;
}
//get the list of top 10 tags and displays different tags in different sizes corresponding to their popularity. ALso, it displays the tags in random order (visually).
function displayTopTags($tagList){

	print("<div class=\"topTags\">");
	$finalList=array();
	$lastTag=end($tagList);
	$baseWeight=$lastTag[1];
	//randomly reorients the list
	shuffle($tagList);
	foreach ($tagList as $row){
		$fontSize=floatval($row[1])/floatval($baseWeight)*10;
		if ($fontSize>55){
				$fontSize=55;
		}
		print("<a class=\"tags\" id=\"tagLink\" href=Blog.php?type=blogsbytag&tagID=".$row[2]." style=\"font-size:".$fontSize."px\">".$row[0]."&nbsp;</a>");
	}
	print("</div>");
}
//given tag ID, the function gets the blog posts associated with that tag ordered by date.
function getBlogsByTag($tagID){
	$blogList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT * FROM Tags NATURAL JOIN Tagged NATURAL JOIN Posts WHERE Tag_ID=".$tagID." ORDER BY Date DESC");

	$result=$mysqli->query($query);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	while($row = mysqli_fetch_array($result)){
		array_push($blogList,$row);
	}
	mysqli_close($mysqli);
	return $blogList;

}


## top Authors functions
//
//gets the top Authors 10
function getTopAuthors(){
	$authorList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT Name,User_ID FROM Users NATURAL JOIN Posts GROUP BY User_ID ORDER BY Count(NumLiked) DESC LIMIT 10");

	$result=$mysqli->query($query);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	while($row = mysqli_fetch_array($result)){
		array_push($authorList,$row );
	}
	mysqli_close($mysqli);
	return $authorList;
}
//displays the list of top 10 Authors
function displayAuthors($authorList){

	print("<div class=\"topAuthors\">");
	print("Most Popular Authors</br>");
	$finalList=array();
	for ($count=0;$count<sizeof($authorList);$count++){
		print("<a id=\"authorLink\" href=Blog.php?type=blogsbyauthor&authorID=".$authorList[$count]["User_ID"]." >".($count+1).". ".$authorList[$count]["Name"]."</a></br>");
	}
	print("</div>");
}
//displays a single author given author tuple
function displayAuthorsSingle($author){
	print("<a id=\"authorLink\" href=Blog.php?type=blogsbyauthor&authorID=".$author["User_ID"].">".$author["Name"]."</a>");
}
//given author ID, the function gets the blog posts associated with that author ordered by date.
function getBlogsByAuthor($authorID){
	$authorList=Array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT * FROM Users NATURAL JOIN Posts WHERE User_ID=".$authorID." ORDER BY Date DESC");

	$result=$mysqli->query($query);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	while($row = mysqli_fetch_array($result)){
		array_push($authorList,$row);
	}
	mysqli_close($mysqli);
	return $authorList;

}
## Set functions

//takes input from form and attempt transaction
//validates checks for file types (cover photo)
//validates teh title, date, tag names
//valides the parragraph
function insertBlogEntry($userID,$title,$post,$url,$date,$tagsRaw){
	$noErrors=TRUE;
	$messageList=array();
	$tagList=array();
	//if picture exist
	$isValidPhoto=checkValidPhoto($url);
	if ($isValidPhoto){
		$tempFile=$url['tmp_name'];
	}
	//checks for at least one of the alphanumeric characters
	if (!preg_match("/[\w]+/",$title)){
		$noErrors=FALSE;
		$errorMessage="Incorrect Blog Title Input";
		array_push($messageList,$errorMessage);
	}
	if (!preg_match("/[\w]+/",$post)){
		$noErrors=FALSE;
		$errorMessage="Incorrect Blog Post Input";
		array_push($messageList,$errorMessage);
	}
	if ($isValidPhoto){
		//only jpg png gif bmp tif files are allowed
		if (!preg_match("/[\w]+(\.jpg){1,1}$/",$url['name'])){
			$noErrors=FALSE;
			$errorMessage="Incorrect Coverphoto Input";
			array_push($messageList,$errorMessage);
		}
	}
	$tagList=explode(",",$tagsRaw);
	foreach($tagList as $tag){
		if (!preg_match("/[\w-_\s]+/",$tag)){
			$noErrors=FALSE;
			$errorMessage="Incorrect Tag Input";
			array_push($messageList,$errorMessage);
		}
	}
	

	//do something with tags
	if ($noErrors){
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		  
		  
		  
		  //sanitizes the inputs
		  $userID=$mysqli->real_escape_string($userID);
		  $title=$mysqli->real_escape_string($title);
		  $post=$mysqli->real_escape_string($post);
		  $time = new DateTime();
		  $path="images/blog/";
		  if ($isValidPhoto){
			$photoURL=$mysqli->real_escape_string($path.$time->getTimestamp().$url['name']);
		  }
		  else{
			$photoURL=NULL;
		  }
		  $date=$mysqli->real_escape_string($date);
		//starts transaction
		$mysqli->autocommit(FALSE);
		$all_query_ok=true;
		//blog post insert attempt
		$blogQuery=sprintf("INSERT INTO Posts (User_ID,Title,Post,Url,Date,NumLiked) VALUES ('$userID','$title','$post','$photoURL','$date',0)");
		$mysqli->query($blogQuery)? null : $all_query_ok=false; 

		//gets the PostID
		$postID=0;
		$postIDQuery=sprintf("SELECT * FROM Posts WHERE User_ID=".$userID." AND Date='".$date."'");
		$postIDQueryResult=$mysqli->query($postIDQuery);

		while($row = mysqli_fetch_array($postIDQueryResult)){
		$postID=$row['Post_ID'];
		}
		
		
		
		//tags insert attempt
		$tagQueryResult=TRUE;
		foreach($tagList as $tag){
			$existQuery=sprintf("SELECT * FROM Tags NATURAL JOIN Tagged WHERE Tag_Name='$tag'");
			$result=$mysqli->query($existQuery);
			$row = $result->fetch_row();
			$test = $row[0];
			if (empty($test)){
				$tagQuery=sprintf("INSERT INTO Tags (Tag_Name) VALUES ('$tag')");
				$mysqli->query($tagQuery)? null : $all_query_ok=false; 
				//if at least 1 fails, then the result returns false
			}

		}
		//gets Tags ID
		$tagIDList=array();
		foreach($tagList as $tag){
			$tagID=0;
			$tagIDQuery=sprintf("SELECT * FROM Tags WHERE Tag_Name='".$tag."'");
			$tagIDQueryResult=$mysqli->query($tagIDQuery);

			while($row = mysqli_fetch_array($tagIDQueryResult)){
				array_push($tagIDList,$row['Tag_ID']);
			}
		}
		
		//tagged insert attempt
		$taggedQueryResult=TRUE;
		foreach($tagIDList as $tagID){
			$taggedQuery=sprintf("INSERT INTO Tagged (Post_ID,Tag_ID) VALUES ('$postID','$tagID')");
			$mysqli->query($taggedQuery)? null : $all_query_ok=false; 
			//if at least 1 fails, then the result returns false

		}
		if ($all_query_ok){
			$mysqli->commit();
			array_push($messageList,"Blog post Inserted");
			if ($photoURL!=NULL){
				move_uploaded_file($tempFile,$photoURL);
				make_thumb($photoURL,"images/blog/thumbs/".basename($photoURL),200,200);
			}
		}
		else{
			$mysqli->rollback(); 
			array_push($messageList,"Your post did not go through");
		}
		
		mysqli_close($mysqli);
		
		
		
	}
	blogEntryMessage($messageList);
	
	
}


function makeBlogInput(){
	if (isset($_POST["submitBlog"])==true){
		
		
		$photo=$_FILES["blogPhoto"];
			$title=$_POST["title"];
			$post=$_POST["post"];
			$tags=$_POST["tags"];
			$userID=$_SESSION['logged'];
			$date=date('Y/m/d H:i:s');

			insertBlogEntry($userID,$title,$post,$photo,$date,$tags);

	}
}

## changes to blog
//make changes to blog
function makeChangeBlogInput(){
	if (isset($_POST["changeBlog"])==true){
		//checks if at least 1 thing is inserted
		$tags=$_POST["tags"];
		$post=$_POST["post"];
		if (isset($_FILES["blogPhoto"])){
			$photo=$_FILES["blogPhoto"];
		}
		else{
			$photo=NULL;
		}
		$checkList=array("post"=>$post,"tags"=>$tags,"photo"=>$photo);
		$postID=$_POST["postID"];
		$userID=$_SESSION['logged'];
		$date=date('Y/m/d H:i:s');
		$isEmpty=atLeastOne($checkList);
		if ($isEmpty==FALSE){
			changeBlogEntry($userID,$postID,$checkList,$date);
		}


	}
	if (isset($_POST["deleteBlog"])==true){
		deletePost($postID=$_POST["postID"]);


	}

}
//checks if at least 1 thing is going to be changed
//if 
function atLeastOne($checkList){
	$isEmpty=TRUE;
	foreach ($checkList as $row){
	if (!empty($row)){
		$isEmpty=FALSE;
	}
			
	}
	return $isEmpty;

}
//attempts to make changes to the database
function changeBlogEntry($userID,$postID,$checkList,$date){
	$post=$checkList["post"];
	$tagsRaw=$checkList["tags"];
	$url=$checkList["photo"];
	$checkPost=FALSE;
	$checkTags=FALSE;
	$checkPhoto=FALSE;
	
	$noErrors=TRUE;
	$messageList=array();
	$tagList=array();
	$tempFile=$url['tmp_name'];
	//see what the insert into database
	//and checks for at least one of the alphanumeric characters
	if (!empty($post)){
		$checkPost=TRUE;
		if (!preg_match("/[\w]+/",$post)){
		$noErrors=FALSE;
		$errorMessage="Incorrect Blog Post Input";
		array_push($messageList,$errorMessage);
		}
	}
	if (!empty($tagsRaw)){
		$checkTags=TRUE;
		$tagList=explode(",",$tagsRaw);
		foreach($tagList as $tag){
			if (!preg_match("/[\w-_\s]+/",$tag)){
				$noErrors=FALSE;
				$errorMessage="Incorrect Tag Input for:".$tag;
				array_push($messageList,$errorMessage);
			}
		}
	}
	if ($url["size"]>0){
		$checkPhoto=TRUE;
		if (!preg_match("/[\w]+(\.jpg){1,1}$/",$url['name'])){
		$noErrors=FALSE;
		$errorMessage="Incorrect Coverphoto Input";
		array_push($messageList,$errorMessage);
		}
	}
	
	

	//do something with tags
	if ($noErrors){
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		  
		  
		  
		  //sanitizes the inputs
		  $userID=$mysqli->real_escape_string($userID);
		  $post=$mysqli->real_escape_string($post);
		  $time = new DateTime();
		  $path="images/blog/";
		  $photoURL=$mysqli->real_escape_string($path.$time->getTimestamp().$url['name']);
		  $date=$mysqli->real_escape_string($date);
		//starts transaction
		$mysqli->autocommit(FALSE);
		$all_query_ok=true;
		
		if ($checkPost){
		//blog post change attempt
		$blogQuery=sprintf("UPDATE Posts SET Post='$post' WHERE Post_ID='$postID'");
		$mysqli->query($blogQuery)? null : $all_query_ok=false; 
		if ($all_query_ok){
			//change the photo option
			$mysqli->commit();
			array_push($messageList,"Blog's Post Change");
		}

		else{
			$mysqli->rollback(); 
			array_push($messageList,"Your post did not go through");
		}
		}
		if ($checkPhoto){
		//blog photo change attempt
		$blogQuery=sprintf("UPDATE Posts SET Url='$photoURL' WHERE Post_ID='$postID'");
		$mysqli->query($blogQuery)? null : $all_query_ok=false; 
		if ($all_query_ok){
			//change the photo option
			$mysqli->commit();
			array_push($messageList,"Blog Photo Changed");
			move_uploaded_file($tempFile,$photoURL);
			make_thumb($photoURL,"images/blog/thumbs/".basename($photoURL),200,200);
		}

		else{
			$mysqli->rollback(); 
			array_push($messageList,"Your post did not go through");
		}
		}
		if ($checkTags){
			//deletes previous tag relations
			foreach($tagList as $tag){
				$tagQuery=sprintf("DELETE FROM Tagged WHERE Post_ID='$postID'");
			$mysqli->query($tagQuery)? null : $all_query_ok=false; 
				//if at least 1 fails, then the result returns false
			}
			
			
			//tags insert attempt
			$tagQueryResult=TRUE;
			foreach($tagList as $tag){
				//select from Tags with tag_name = $tag
				//if it doesn't exist
				$existQuery=sprintf("SELECT * FROM Tags NATURAL JOIN Tagged WHERE Tag_Name='$tag'");
				$result=$mysqli->query($existQuery);
				if ($result !== false){
					$tagQuery=sprintf("INSERT INTO Tags (Tag_Name) VALUES ('$tag')");
					$mysqli->query($tagQuery)? null : $all_query_ok=false; 
					//if at least 1 fails, then the result returns false
				}
			}
		
			//gets Tags ID
			$tagIDList=array();
			foreach($tagList as $tag){
				$tagID=0;
				$tagIDQuery=sprintf("SELECT * FROM Tags WHERE Tag_Name='".$tag."'");
				$tagIDQueryResult=$mysqli->query($tagIDQuery);

				while($row = mysqli_fetch_array($tagIDQueryResult)){
					array_push($tagIDList,$row['Tag_ID']);
				}
			}
			
			//tagged insert attempt
			$taggedQueryResult=TRUE;
			foreach($tagIDList as $tagID){
				$taggedQuery=sprintf("INSERT INTO Tagged (Post_ID,Tag_ID) VALUES ('$postID','$tagID')");
				$mysqli->query($taggedQuery)? null : $all_query_ok=false; 
				//if at least 1 fails, then the result returns false
			}
		if ($all_query_ok){
			//change the photo option
			$mysqli->commit();
			array_push($messageList,"Blog Tags Changed");
		}

		else{
			$mysqli->rollback(); 
			array_push($messageList,"Your tag changes did not go through");
		}
		}

		
		mysqli_close($mysqli);
		
		
		
	}
	blogEntryMessage($messageList);
	
	
}
//checks if the extension is valid for the file uploaded
//if not return error message
function checkValidPhoto($potentialImage){
	$imageType=$potentialImage['type'];
	echo "".$imageType;
	if(preg_match("/^(image\/)/",$imageType)){
		return TRUE;
	}
	else{
		return FALSE;
	}
}

##comments insertion



//comments on form error made by the user
function blogEntryMessage($messageList){
	print("<div id=\"postInputMessage\">");
	foreach($messageList as $message){
		print($message."</br>");
	}
		print("</div>");

}
//validates and attempts to insert new comments
function insertComments($postID,$userID,$post,$date){
	$noErrors=TRUE;
	$messageList=array();
	$tagList=array();
	//checks for at least one of the alphanumeric characters
	if (!preg_match("/[\w]+/",$post)){
		$noErrors=FALSE;
		$errorMessage="Incorrect comment input";
		array_push($messageList,$errorMessage);
	}
	//do something with tags
	if ($noErrors){
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		  //sanitizes the inputs
		  $userID=$mysqli->real_escape_string($userID);
		  $post=$mysqli->real_escape_string($post);
		  $date=$mysqli->real_escape_string($date);
		//starts transaction
		$mysqli->autocommit(FALSE);
		$all_query_ok=true;
		//comments post insert attempt
		$commentQuery=sprintf("INSERT INTO Comments (Post_ID,User_ID,Post,Date) VALUES ('$postID','$userID','$post','$date')");
		$mysqli->query($commentQuery)? null : $all_query_ok=false; 
		
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		$all_query_ok ? array_push($messageList,"Comments Inserted") : array_push($messageList,"Your comments did not go through");
		mysqli_close($mysqli);
	}
	blogEntryMessage($messageList);
}
function displayCommentInput($blogID){
	print("<div class='well well-small'>");
	$commenterName=getUser($_SESSION["logged"]);
	
	$date=new DateTime();
	$date = $date->format('Y-m-d H:i:s');
	print("<form action=\"\" method=\"post\">");
	print("<div id=\"commentAuthor\" class=\"DisplayCommentsUserName\">".$commenterName['Name']." (".$commenterName['Username'].") on ".$date."</div>");
	print("New Comment: <input type=\"text\" name=\"post\">
	<input type=\"hidden\" name=\"postID\" value='$blogID'>
	<input type=\"submit\" class=\"btn btn-primary\" name=\"submitComment\">
	</form>");
	print("</div>");
}
## like methods

//if the like for the post correlating to the member exist in database
//display the like button or else display liked message
function displayLikeButton($postID,$userID){
	if (!(isset($_POST['likeSubmit']) && !empty($_POST['likeSubmit']))){
		//	processLike($postID,$userID);
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		//comments post insert attempt
		$likeQuery=sprintf("SELECT * FROM LikedPost WHERE Post_ID='$postID' AND User_ID='$userID'");
		$result=$mysqli->query($likeQuery);
		//$row = $result->fetch_row();
		///$test = $row[0];
		//if (empty($test)){
		if($result->num_rows == 0) {
		//	print($userID);
			print("<input type=\"submit\" class=\"btn btn-primary\" name=\"likeSubmit\" value=\"Like\">");
		}
		
		mysqli_close($mysqli);
	}
}
//add the like to the post
function processLike($postID,$userID){
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		//starts transaction
		$mysqli->autocommit(FALSE);
		$all_query_ok=true;
		//comments post insert attempt
		$likeQuery=sprintf("INSERT INTO LikedPost (Post_ID,User_ID) VALUES ('$postID','$userID')");
		$mysqli->query($likeQuery)? null : $all_query_ok=false; 
		$likeQuery=sprintf("UPDATE Posts SET NumLiked=NumLiked+1 WHERE Post_ID='$postID'");
		$mysqli->query($likeQuery)? null : $all_query_ok=false; 
		
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		if ($all_query_ok){
			print("Successfully Liked");
		}

		mysqli_close($mysqli);
}
## display the list of blog title for dropdown list
//displaying blog name and having the dropdownlist value being blog's blogID
function displayBlogList(){
		$userID=$_SESSION['logged'];
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		//comments post insert attempt
		$likeQuery=sprintf("SELECT * FROM Posts WHERE User_ID='$userID'");
		$result=$mysqli->query($likeQuery);
		
		print("<select name=\"postID\">");
		while($row = mysqli_fetch_array($result)){
			print("<option value=\"".$row['Post_ID']."\">".$row['Title']."</option>");
		}
		print("</select>");
		mysqli_close($mysqli);
}
## delete Posts
	
function deletePost($postID){
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		//comments post insert attempt
		$all_query_ok=true;
		$mysqli->autocommit(FALSE);
		$deleteQuery=sprintf("DELETE FROM Posts WHERE Post_ID='$postID'");
		$mysqli->query($deleteQuery)? null : $all_query_ok=false; 
		
		$deleteQuery=sprintf("DELETE FROM Tagged WHERE Post_ID='$postID'");
		$mysqli->query($deleteQuery)? null : $all_query_ok=false; 
		
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		if ($all_query_ok){
			print("Successfully Deleted the Post");
		}

		mysqli_close($mysqli);

}

##edit/delete comments
function editComment($postID,$userID,$date,$post){
	
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		//comments post insert attempt
		$all_query_ok=true;
		$mysqli->autocommit(FALSE);
		$changeCommentQuery=sprintf("UPDATE Comments SET Post='$post' WHERE Post_ID='$postID' AND User_ID='$userID' AND Date='$date'");
		$mysqli->query($changeCommentQuery)? null : $all_query_ok=false; 
		
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		if ($all_query_ok){
			print("Successfully changed the comment");
		}

		mysqli_close($mysqli);

}
function deleteComment($postID,$userID,$date){
	
		$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		//comments post insert attempt
		$all_query_ok=true;
		$mysqli->autocommit(FALSE);
		$deleteCommentQuery=sprintf("DELETE FROM Comments WHERE Post_ID=$postID AND User_ID=$userID AND Date='$date'");
		$mysqli->query($deleteCommentQuery)? null : $all_query_ok=false; 
		
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		if ($all_query_ok){
			print("Successfully deleted the comment");
		}
		else{
			print("Failed to delete the comment");
		}

		mysqli_close($mysqli);

}
##display all tags and authors
//displays all tags
function displayAllTags(){
	$tagList=getAllTags();
	print("Tags<br>");
	print("<div class=\"allTags\">");
	foreach ($tagList as $row){
		print("<a class=\"tags\" id=\"tagAll\" href=Blog.php?type=blogsbytag&tagID=".$row['Tag_ID'].">".$row['Tag_Name']."&nbsp;</a><br>");
	}
	print("</div>");
}
//gets all tags
//outputs a list of them
function getAllTags(){
	$tagList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		$query=sprintf("SELECT * FROM Tagged NATURAL JOIN Tags");

		 $result=$mysqli->query($query);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $query;
				die($message);
			}
		  while($row = mysqli_fetch_array($result)){
				array_push($tagList,$row);
			}
			mysqli_close($mysqli);
		  return $tagList;
  
}
//gets all authors
//outputs a list of them
function getAllAuthors(){
	$authorList=array();
	$mysqli = new mysqli("localhost", "Jirex","password", "DatabaseName");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT Name,User_ID FROM Users NATURAL JOIN Posts GROUP BY User_ID ORDER BY SUM(NumLiked) DESC");

	$result=$mysqli->query($query);

	if (!$result) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	while($row = mysqli_fetch_array($result)){
		array_push($authorList,$row );
	}
	mysqli_close($mysqli);
	return $authorList;
}
//displays all authors
function displayAllAuthors(){
	$authorList=getAllAuthors();
	print("<div class=\"allAuthors\">");
	print("Authors</br>");
	$finalList=array();
	for ($count=0;$count<sizeof($authorList);$count++){
		print("<a id=\"authorLink\" href=Blog.php?type=blogsbyauthor&authorID=".$authorList[$count]["User_ID"]." >".($count+1).". ".$authorList[$count]["Name"]."</a></br>");
	}
	print("</div>");
}


?>