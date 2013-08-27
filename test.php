<?php
searchBlog("i");
function searchBlog($input){
	$input="%".$input."%";
	$outputList=array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
		if (mysqli_connect_errno($mysqli))
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		   

		$result=$mysqli->query("SELECT * FROM Posts NATURAL JOIN Tagged NATURAL JOIN Tags WHERE Title LIKE '$input' OR Post LIKE '$input' OR Tag_Name LIKE '$input'");
			while($row = mysqli_fetch_array($result)){
			$postID=$row['Post_ID'];
			$postName=$row['Title'];
			$outputList[$postName]='Blog.php?type=blog&blogID='.$postID;
			}
			foreach($outputList as $row){
			echo "output".$row;
			}
		mysqli_close($mysqli);
	
	
}
?>