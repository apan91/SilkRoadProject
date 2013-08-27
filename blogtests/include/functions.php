<?php
##all purpose functions

//requires the post index number, and outputs the result in a associative array.
function getSortedBlog($sortMethod){
	$blogList=array();
	
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
				array_push($tagList,$row["Tag_Name"]);
			}
			mysqli_close($mysqli);
		  return $tagList;
  
}
//given PostID, fetches all the comments for that post ordered by dateTime
function getComments($postID){
	
	$commentsList=array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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




##blog summary functions

//outputs the blog summary 
function displayBlogSummary($beginningIndex,$endingIndex,$sortedList){
	//makes sure it doesnt not go out of bound
	if ($endingIndex>(sizeof($sortedList)-1)){
		$endingIndex=(sizeof($sortedList));
	}
	For ($count=$beginningIndex;$count<=$endingIndex-1;$count++){
		DisplaySummaryTitle($sortedList[$count]["Title"]);
		DisplaySummaryAuthor($sortedList[$count]["User_ID"]);
		DisplaySummaryDate($sortedList[$count]["Date"]);
		DisplaySummaryTags($sortedList[$count]["Post_ID"]);
		DisplaySummaryPost($sortedList[$count]["Post"]);
		//DisplaySummaryCoverPic($sortedList[$count]["Url"]);
		DisplaySummaryNumLikes($sortedList[$count]["NumLiked"]);
		DisplaySummaryUrl($sortedList[$count]["Post_ID"]);
	}
}
//displays the formated title of the summary
function DisplaySummaryTitle($title){
	print("<div class=\"blogSummeryTitle\">".$title."</div>");

}
//displays the formated author of the summary
function DisplaySummaryAuthor($authorID){
	$author=getUser($authorID);
	print("<div class=\"blogSummeryAuthor\">Written by: ".$author["Name"]."</div>");
}
//implode all the tags, seperated by comma
function DisplaySummaryTags($postID){
	$tagList=implode(",",getTags($postID));
	print("<div class=\"blogSummeryTags\">".$tagList."</div>");
}
//displays the formated paragraph of the blog summary limit to 100 ch1aracters)
function DisplaySummaryPost($post){
	$post=substr($post,0,100);
	print("<div class=\"blogSummeryPost\">".$post."...</div>");

}
//displays the formated cover photo of the summary
function DisplaySummaryCoverPic($url){
	print("<div class=\"blogSummeryCoverPic\">".$url."</div>");
}
//displays the formated date of the summary
function DisplaySummaryDate($date){
	print("<div class=\"blogSummeryDate\">&nbsp on ".$date."</div>");
}
//displays the formated number of likes of the summary
function DisplaySummaryNumLikes($numLikes){
	print("<div class=\"blogSummeryNumLikes\">Total Likes: ".$numLikes."</div>");
}
//displays the link for the summary that will pass info via URL for the blog page to process (using GET)
function DisplaySummaryUrl($postID){
	print("<div class=\"blogSummeryUrl\"><a href=\"blog.php?type=blog&blogID=".$postID."\">See the full blog<a></div>");
}

##section for displaying the full blog given args that was passed through the url (GET)
//given blog id, get the content of the blog
function getSingleBlog($postID){

	$post=array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	$post=getSingleBlog($postID);
	$tagList=getTags($postID);
	$commentsList=getComments($postID);
	$authorID=$post["User_ID"];
	DisplayBlogCoverPic($post["Url"]);
	DisplayBlogTitle($post["Title"]);
	DisplayBlogAuthor($authorID);
	DisplayBlogDate($post["Date"]);
	DisplayBlogTags($postID);
	DisplayBlogPost($post["Post"]);
	DisplayBlogNumLikes($post["NumLiked"]);
	displayComments($commentsList);
}
//displays the comments
function displayComments($commentsList){
	print("Comments Made By Members</br>");
	foreach ($commentsList as $row){
	$commenterName=getUser($row["User_ID"]);
	$date=$row["Date"];
	$post=$row["Post"];
	print("<div class=\"DisplayCommentsUserName\">".$commenterName["Name"]."</div>");
	print("<div class=\"DisplayCommentsDate\">".$date."</div>");
	print("<div class=\"DisplayCommentsPost\">".$post."</div>");
	}
}
//displays the formated title of the blog
function DisplayBlogTitle($title){
	print("<div class=\"blogTitle\">".$title."</div>");

}
//displays the formated author of the blog
function DisplayBlogAuthor($authorID){
	$author=getUser($authorID);
	print("<div class=\"blogAuthor\">Written by: ".$author["Name"]."</div>");
}
//implode all the tags, seperated by comma
function DisplayBlogTags($postID){
	$tagList=implode(",",getTags($postID));
	print("<div class=\"blogTags\">".$tagList."</div>");
}
//displays the formated paragraph of the blog
function DisplayBlogPost($post){
	print("<div class=\"blogPost\">".$post."</div>");

}
//displays the formated cover photo
function DisplayBlogCoverPic($url){
	print("<div class=\"blogCoverPic\">".$url."</div>");
}
//displays the formated date
function DisplayBlogDate($date){
	print("<div class=\"blogDate\">&nbsp on ".$date."</div>");
}
//displays the formated number of likes
function DisplayBlogNumLikes($numLikes){
	print("<div class=\"blogNumLikes\">Total Likes: ".$numLikes."</div>");
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
		array_push($pageList,"<a href=\"blog?type=blogsummary&selectedpage=".($count+1)."&numperpage=".$numPerPage."\">".($count+1)."</a>");
	}
	implode("&nbsp",$pageList);
	print("Pages:");
	print(implode("&nbsp",$pageList));

}
//opens the database and counts how many blog posts exists
function getTotalEntries(){
$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
		array_push($tagList,$row );

	}
	mysqli_close($mysqli);
	return $tagList;
}
//get the list of top 10 tags and displays different tags in different sizes corresponding to their popularity. ALso, it displays the tags in random order (visually).
function displayTags($tagList){

	print("<div class=\"topTags\">");
	$finalList=array();
	$lastTag=end($tagList);
	$baseWeight=$lastTag[1];
	//randomly reorients the list
	shuffle($tagList);
	foreach ($tagList as $row){
		$fontSize=floatval($row[1])/floatval($baseWeight)*15;
		if ($fontSize>50){
				$fontSize=50;
		}
		print("<a id=\"tagLink\" href=blog.php?type=blogsbytag&tagID=".$row[2]." style=\"font-size:".$fontSize."px\">".$row[0]."</a>");
	}
	print("</div>");
}
//given tag ID, the function gets the blog posts associated with that tag ordered by date.
function getBlogsByTag($tagID){
	$blogList=array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
	if (mysqli_connect_errno($mysqli))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	$query=sprintf("SELECT Name,User_ID FROM Users NATURAL JOIN Posts GROUP BY User_ID ORDER BY Count(User_ID) DESC LIMIT 10");

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
		print("<a id=\"authorLink\" href=blog.php?type=blogsbyauthor&authorID=".$authorList[$count]["User_ID"]." >".($count+1).". ".$authorList[$count]["Name"]."</a></br>");
	}
	print("</div>");
}
//given author ID, the function gets the blog posts associated with that author ordered by date.
function getBlogsByAuthor($authorID){
	$authorList=Array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	//only jpg png gif bmp tif files are allowed
	if (!preg_match("/[\w]+(\.jpg|\.png|\.gif|\.bmp|\.tif){1,1}$/",$url)){
		$noErrors=FALSE;
		$errorMessage="Incorrect Coverphoto Input";
		array_push($messageList,$errorMessage);
	}
	$tagList=explode(",",$tagsRaw);
	foreach($tagList as $tag){
		if (!preg_match("/[\w-_\s]+/",$tag)){
			$noErrors=FALSE;
			$errorMessage="Incorrect Tag Input for:".$tag;
			array_push($messageList,$errorMessage);
		}
	}
	

	//do something with tags
	if ($noErrors){
		$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		  
		  
		  
		  //sanitizes the inputs
		  $userID=$mysqli->real_escape_string($userID);
		  $title=$mysqli->real_escape_string($title);
		  $post=$mysqli->real_escape_string($post);
		  $url=$mysqli->real_escape_string($url);
		  $date=$mysqli->real_escape_string($date);
		//starts transaction
		$mysqli->autocommit(FALSE);
		$all_query_ok=true;
		//blog post insert attempt
		$blogQuery=sprintf("INSERT INTO Posts (User_ID,Title,Post,Url,Date,NumLiked) VALUES ('$userID','$title','$post','$url','$date',0)");
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
			$tagQuery=sprintf("INSERT INTO Tags (Tag_Name) VALUES ('$tag')");
		$mysqli->query($tagQuery)? null : $all_query_ok=false; 
			//if at least 1 fails, then the result returns false

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
		$all_query_ok ? $mysqli->commit() : $mysqli->rollback(); 
		$all_query_ok ? array_push($messageList,"Blog post Inserted") : array_push($messageList,"Your post did not go through");
		mysqli_close($mysqli);
		
		
		
	}
	blogEntryMessage($messageList);
	
	
}
function displayBlogInput(){
	print("
	<form action=\"\" method=\"post\">
	Title: <input type=\"text\" name=\"title\">
	Post: <input type=\"text\" name=\"post\">
	url: <input type=\"text\" name=\"url\">
	tags: <input type=\"text\" name=\"tags\">
	<input type=\"submit\" name=\"submitBlog\">
	</form>
	");
	if (isset($_POST["submitBlog"])==true){
		
		$title=$_POST["title"];
		$post=$_POST["post"];
		$url=$_POST["url"];
		$tags=$_POST["tags"];
		$userID=$_SESSION['logged'];
		$date=date('Y/m/d H:i:s');

		insertBlogEntry($userID,$title,$post,$url,$date,$tags);
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
		$errorMessage="Incorrect Blog Post Input";
		array_push($messageList,$errorMessage);
	}
	//do something with tags
	if ($noErrors){
		$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
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
	print("
	<form action=\"\" method=\"post\">
	Comment: <input type=\"text\" name=\"post\">
	<input type=\"submit\" name=\"submitComment\">
	</form>
	");
	if (isset($_POST["submitComment"])==true){
		
		$post=$_POST["post"];
		$userID=$_SESSION['logged'];
		$date=date('Y/m/d H:i:s');
		insertComments($blogID,$userID,$post,$date);
	}
}





?>