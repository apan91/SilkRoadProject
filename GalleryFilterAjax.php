<?php
	include "includes/MyTools.php";
	if (!isset($_SESSION))
		session_start();
	
	// does a dynamic ajax request ever time user types anything
	if(isset($_GET['country'])) {
		$country = mysql_real_escape_string($_GET['country']);
		$chapter = mysql_real_escape_string($_GET['chapter']);
		$activity = mysql_real_escape_string($_GET['activity']);
		$startdate = mysql_real_escape_string($_GET['start']);
		$enddate = mysql_real_escape_string($_GET['end']);
		$rid = mysql_real_escape_string($_GET['rid']);
		
		displayFilteredPhotos($country, $chapter, $activity, $startdate, $enddate, $rid);
	}
?>