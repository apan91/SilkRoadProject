<?php			
			
// pass in one or more sql statement as parameter and the result is returned
function multi_query($sql) {
	$mysqli = new mysqli('localhost', 'Jirex', 'xek5hsh7vhk', 'info230_SP13FP_Jirex');
	
	if($mysqli->errno) {
		print($mysqli->error);
		exit();	
	}
	
	$result = $mysqli->multi_query($sql);
	$mysqli->close();
	return $result;
}

// pass in exactly one sql statement as parameter and the result is returned
function query($sql) {
	$mysqli = new mysqli('localhost', 'Jirex', 'xek5hsh7vhk', 'info230_SP13FP_Jirex');
	
	if($mysqli->errno) {
		print($mysqli->error);
		exit();	
	}
	
	$result = $mysqli->query($sql);
	$mysqli->close();
	return $result;
}

function displayAllPhotos($rid) {
	$sql = "SELECT * FROM Photos NATURAL JOIN Activities_IN NATURAL JOIN Chapters_IN NATURAL JOIN Countries_IN NATURAL JOIN Regions_IN";
	$filters = array();
	
	if($rid != -1) {
		$filters[] = "Region_ID = $rid";	
	}
	
	$cn = 0;
	foreach($filters as $filter) {
		if($cn == 0) {
			$sql .= " WHERE ".$filter;	
		} else {
			$sql .= " AND ".$filter;
		}
		
		$cn++;
	}

	$result = query($sql);
	
	generatePhotoTable($result);
}

function displayAllRegions() {
	$sql = "SELECT * FROM Regions";
	$result = query($sql);
	
	generateRegionTable($result);

}

function displayFilteredPhotos($country, $chapter, $activity, $startdate, $enddate, $rid) {
	$sql = "SELECT * FROM Photos NATURAL JOIN Activities_IN NATURAL JOIN Chapters_IN NATURAL JOIN Countries_IN NATURAL JOIN Regions_IN";
	$filters = array();
	
	if($rid != -1) {
		$filters[] = "Region_ID = $rid";	
	}
	
	if ($country != -1) {
		$filters[] = "Country_ID = $country";	
	}
	
	if ($chapter != -1) {
		$filters[] = "Chapter_ID = $chapter";	
	}
	
	if ($activity != -1) {
		$filters[] = "Activity_ID = $activity";	
	}
	
	$filters[] = "Date_Taken BETWEEN '$startdate' AND '$enddate'";
	
	$cn = 0;
	foreach($filters as $filter) {
		if($cn == 0) {
			$sql .= " WHERE ".$filter;	
		} else {
			$sql .= " AND ".$filter;
		}
		
		$cn++;
	}

	$result = query($sql);
	generatePhotoTable($result);
}

function generateRegionTable($result) {
	echo "<table class=\"table\">";
	
	$counter = 0;
	while ($array = $result->fetch_assoc()) {
		$rid = $array['Region_ID'];
		$name = $array['Name'];
		$thumbnail = $array['Thumbnail'];
		$url = "Gallery.php?rid=$rid";
	
		if($counter % 3 == 0) {
			echo "<tr class=\"\">";	
		}
	
		echo "<td class=\"span3 phototd\">";
		echo "<a href='$url'>";
		echo "<img src='$thumbnail' alt='$name' />";
		echo "</a><br>";
		
		if((isset($_SESSION['logged']) && !empty($_SESSION['logged']))) {

			//retrieve the userid from the logged session
			$userid = $_SESSION['logged'];
			$userquery = query("SELECT * FROM Users WHERE User_ID = '$userid'");
			$user = $userquery->fetch_assoc();
			$userperm = $user['Permission'];
			
			if($userperm == 4) {
				echo "<br><div>";	
				
				echo "<form class='edit' method='post' action='Gallery.php?sort=Region'>";		
				echo "<input type='hidden' name='region_id' value=$rid />";	
				echo "<input type='hidden' name='region_name' value=$name />";				
				echo "Title: <input type='text' name='new_region_name' value='$name'/></br>";
				echo "<input class='btn' type='submit' name='changeRegion' value='Update Region' />";
				echo "</form>";
				
				echo "</div>";
				
			} 	
		} else {
			echo "Region: $name";
		}
		
		echo "</td>";
		
		if($counter != 1 && $counter-1 % 3 == 0) {
			echo "</tr>";	
		}
		
		$counter++;
	}
	
	echo "</table>";
}

function generatePhotoTable($result) {
	echo "<table class=\"table\">";
	
	$counter = 0;
	while ($array = $result->fetch_assoc()) {
		$pid = $array['Photo_ID'];
		$name = $array['Name'];
		$caption = $array['Caption'];
		$url = $array['URL'];
		$date = $array['Date_Taken'];
		$thumbnail = $array['Thumbnail'];
		
		$countryid = $array['Country_ID'];
		$chapterid = $array['Chapter_ID'];
		$activityid = $array['Activity_ID'];

	
		if($counter % 3 == 0) {
			echo "<tr class=\"\">";	
		}
	
		echo "<td class=\"span3 phototd\">";
		
		echo "<a href='$url' rel='lightbox' title='$caption'>";
		
		echo "<img src='$thumbnail' alt='$caption' />";
		
		echo "</a>";
		
		
		if((isset($_SESSION['logged']) && !empty($_SESSION['logged']))) {

			//retrieve the userid from the logged session
			$userid = $_SESSION['logged'];
			$userquery = query("SELECT * FROM Users WHERE User_ID = '$userid'");
			$user = $userquery->fetch_assoc();
			$userperm = $user['Permission'];
			
			if($userperm == 4) {
				echo "<br><br><div>";	
				echo "<form class='edit' method='post' action='Gallery.php'>";		
				echo "<input type='hidden' name='photo_id' value=$pid />";	
				echo "<input type='hidden' name='photo_name' value=$name />";				
				echo "<input type='hidden' name='photo_caption' value=$caption />";

				echo "Title: <input type='text' name='new_name' value='$name'/></br>";
				echo "Caption: <input type='text' name='new_caption' value='$caption'/></br>";
				
				echo 'Country: <select name="new_country">';
				$r1 = query("SELECT * from Countries");
				while ($b = $r1 -> fetch_assoc()){
					$bid = $b['Country_ID'];
					$bname = $b['Name'];
					$selected = $bid == $countryid ? " selected" : "";
					echo "<option value='$bid'$selected>$bname</option>";
				}
	  	   		echo '</select></br>';
	  	   		
	  	   		echo 'Chapter: <select name="new_chapter">';
  				echo "<option value='-1'></option>";
  				$r2 = query("SELECT * from Chapters");
  				while ($a = $r2 -> fetch_assoc()){
  					$aid = $a['Chapter_ID'];
  					$aname = $a['Name'];
  					$selected = $aid == $chapterid ? " selected" : "";
  					echo "<option value='$aid'$selected>$aname</option>";
  				}
	  	   		echo '</select></br>';
	  	   		
	  	   		echo 'Activity: <select name="new_activity">';
	  			echo "<option value='-1'></option>";
	  			$r3 = query("SELECT * from Activities");
	  			while ($a = $r3 -> fetch_assoc()){
	  				$id = $a['Activity_ID'];
	  				$name = $a['Name'];
	  				$selected = $id == $activityid ? " selected" : "";
	  				echo "<option value='$id'$selected>$name</option>";
	  			}
	  	   		echo '</select></br>';

				
				echo "<input class='btn' type='submit' name='change' value='Update Photo' />";
				echo "<input class='btn' type='submit' name='delete' value='Delete Photo' />";
				
				echo "</form>";
				echo "</div>";
			} 
		} else {
			echo "<br>Name: $name";
			echo "<br>Caption: $caption";		
		}	
		
		echo "</td>";
		
		if($counter != 1 && $counter-1 % 3 == 0) {
			echo "</tr>";	
		}
		
		$counter++;
	}
	
	echo "</table>";	
}

// Got from http://davidwalsh.name/create-image-thumbnail-php
function make_thumb($src, $dest, $desired_width, $desired_height) {
	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	//$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

// sql to delete a photo with aid and pid
function deletePhoto($pid) {
	$sql = "DELETE FROM Photos WHERE Photo_ID = $pid;";
	$sql .= "DELETE FROM Activities_IN WHERE Photo_ID = $pid;";
	$sql .= "DELETE FROM Chapters_IN WHERE Photo_ID = $pid;";
	$sql .= "DELETE FROM Countries_IN WHERE Photo_ID = $pid";

	multi_query($sql);
}

function deleteChapter($cid) {
	$sql = "DELETE FROM Chapters WHERE Chapter_ID = $cid;";
	$sql .= "DELETE FROM Chapters_IN WHERE Chapter_ID = $cid";
	multi_query($sql);
}

function deleteActivity($aid) {
	$sql = "DELETE FROM Activities WHERE Activity_ID = $aid;";
	$sql .= "DELETE FROM Activities_IN WHERE Activity_ID = $aid";
	multi_query($sql);
}

// sql to change photo caption
function changePhotoCaption($pid, $name, $caption, $country, $chapter, $activity) {
	$sql = "UPDATE Photos SET Name = \"$name\" WHERE Photo_ID = $pid;";
	$sql .= "UPDATE Photos SET Caption = \"$caption\" WHERE Photo_ID = $pid;";
	$sql .= "UPDATE Countries_IN SET Country_ID = \"$country\" WHERE Photo_ID = $pid;";
	$sql .= "UPDATE Chapters_IN SET Chapter_ID = \"$chapter\" WHERE Photo_ID = $pid;";
	$sql .= "UPDATE Activities_IN SET Activity_ID = \"$activity\" WHERE Photo_ID = $pid";
	multi_query($sql);
}

function changeRegionTitle($rid, $name) {
	$sql = "UPDATE Regions SET Name = \"$name\" WHERE Region_ID = $rid";
	query($sql);
}

function changeCountryTitle($cid, $name) {
	$sql = "UPDATE Countries SET Name = \"$name\" WHERE Country_ID = $cid";
	query($sql);
}

function changeChapterTitle($cid, $name) {
	$sql = "UPDATE Chapters SET Name = \"$name\" WHERE Chapter_ID = $cid";
	query($sql);
}

function changeActivityTitle($aid, $name) {
	$sql = "UPDATE Activities SET Name = \"$name\" WHERE Activity_ID = $aid";
	query($sql);
}

/**
 * Given $tablename, and $columns Array
 * looks through $columns for $searchfield
 *
 * i.e. search("Projects", Array("Name", "Caption"), "village")
 */
function search($tablename, $columns, $searchfield){
	$query = "
		SELECT * FROM $tablename
		WHERE ";
	
	$where = Array();
	foreach ($columns as $column){
		array_push($where, " $column LIKE '%$searchfield%' "); 
	}
	
	$query .= implode("OR", $where);
	return $query;
}

function searchProject($term){
	$result = query(search("Projects", Array("Name", "Caption"), $term));
	
	if ($result -> num_rows == 0){
		echo "No projects found<br>";
		return;
	}
	echo "<ul style='list-style-type:square'>";
	while ($array = $result->fetch_assoc()) {
		echo "<li><a href='Projects.php?PID=" . $array['Project_ID'] . "'>";
		echo $array['Name'] . "</a></li>";
	}
	echo "</ul>";
}

function searchDonation($term){
	$result = query(search("Causes", Array("Name", "Description"), $term));
	
	if ($result -> num_rows == 0){
		echo "No donations found<br>";
		return;
	}
	echo "<ul style='list-style-type:square'>";
	while ($array = $result->fetch_assoc()) {
		echo "<li><a href='ViewCauses.php?CID=" . $array['Cause_ID'] . "'>";
		echo $array['Name'] . "</a></li>";
	}
	echo "</ul>";
}

function searchFile($term){
	$result = query(search("Files", Array("Name", "Caption"), $term));
	
	if ($result -> num_rows == 0){
		echo "No files found<br>";
		return;
	}
	echo "<ul style='list-style-type:square'>";
	while ($array = $result->fetch_assoc()) {
		echo "<li><a href='" . $array['URL'] . "'>";
		echo $array['Name'] . "</a></li>";
	}
	echo "</ul>";
}

// generates a table of all photos that fit the search caption criteria
function generateSearchTable2($search_phrase) {
	echo "<table class=\"table\">";
	
	$sql = "SELECT * FROM Photos WHERE Caption LIKE \"%$search_phrase%\" OR Name LIKE \"%$search_phrase%\"";
	$result = query($sql);		
	
	$counter = 0;
	while ($array = $result->fetch_assoc()) {
		$pid = $array['Photo_ID'];
		$name = $array['Name'];
		$caption = $array['Caption'];
		$url = $array['URL'];
		$date = $array['Date_Taken'];
		$thumbnail = $array['Thumbnail'];
	
		if($counter % 3 == 0) {
			echo "<tr class=\"\">";	
		}
	
		echo "<td class=\"span3 phototd\">";
		
		echo "<a href='$url' rel='lightbox' title='$caption'>";
		
		echo "<img src='$thumbnail' alt='$caption' />";
		
		echo "</a>";
		
		echo "<br>Name: $name";
		echo "<br>Caption: $caption";	
		
		echo "</td>";
		
		if($counter != 1 && $counter-1 % 3 == 0) {
			echo "</tr>";	
		}
		
		$counter++;
	}
	
	if($counter == 0) {
		echo "No photos found<br>";	
	}
	
	echo "</table>";
}

// generates a table of all photos that fit the search caption criteria
function generateSearchTable($search_phrase) {
	echo "<h1>Search Results: \"$search_phrase\"</h1></br>";
		echo "<table class=\"table\">";
	
	$sql = "SELECT * FROM Photos WHERE Caption LIKE \"%$search_phrase%\" OR Name LIKE \"%$search_phrase%\"";
	$result = query($sql);		
	
	$counter = 0;
	while ($array = $result->fetch_assoc()) {
		$pid = $array['Photo_ID'];
		$name = $array['Name'];
		$caption = $array['Caption'];
		$url = $array['URL'];
		$date = $array['Date_Taken'];
		$thumbnail = $array['Thumbnail'];
	
		if($counter % 3 == 0) {
			echo "<tr class=\"\">";	
		}
	
		echo "<td class=\"span3 phototd\">";
		
		echo "<a href='$url' rel='lightbox' title='$caption'>";
		
		echo "<img src='$thumbnail' alt='$caption' />";
		
		echo "</a>";
		
		echo "<br>Name: $name";
		echo "<br>Caption: $caption";	
		
		echo "</td>";
		
		if($counter != 1 && $counter-1 % 3 == 0) {
			echo "</tr>";	
		}
		
		$counter++;
	}
	
	if($counter == 0) {
		echo "<tr><td>";
		echo "<h4>Specified Title Or Caption Not Found In Photo Gallery!</h4>";	
		echo "</td></tr>";
	}
	
	echo "</table>";	
}

function generateBlogList($term) {
	$results = searchBlog($term);
	
	if (count($results) == 0){
		echo "No blogs found<br>";
		return;
	}
	
	echo "<ul style='list-style-type:square'>";
	foreach ($results as $name => $link) {
		echo "<li><a href=\"$link\">";
		echo $name."</a></li>";
	}
	echo "</ul>";
}

function searchBlog($input){
	$input="%".$input."%";
	$outputList = array();
	$mysqli = new mysqli("localhost", "Jirex","xek5hsh7vhk", "info230_SP13FP_Jirex");
	if (mysqli_connect_errno($mysqli)) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
		
	$result=$mysqli->query("SELECT * FROM Posts NATURAL JOIN Tagged NATURAL JOIN Tags WHERE Title LIKE '$input' OR Post LIKE '$input' OR Tag_Name LIKE '$input'");
	
	while($row = mysqli_fetch_array($result)){
		$postID=$row['Post_ID'];
		$postName=$row['Title'];
		$outputList[$postName]="Blog.php?type=blog&blogID=".$postID;
	}
	mysqli_close($mysqli);
	
	return $outputList;
}
?>