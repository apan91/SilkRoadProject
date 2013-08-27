<?php
	include "includes/MyTools.php";
	
	// does a dynamic ajax request ever time user types anything
	if(isset($_GET['searchValue'])) {
		$search = mysql_real_escape_string($_GET['searchValue']);
		
		if(!preg_match('/^[0-9a-zA-Z\s]+$/', $search)) {
			echo "<div class='alert alert-error'>Please provide a valid caption with letters and numbers only!</div>";
			
		} else {
			generateSearchTable($search);
		}
	}
?>