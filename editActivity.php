<?php
	require('includes/header.php');
?>
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
			include('adminCheck.php');					
		?>
			<h1>Edit Activity</h1>
			<br/>
		<?php
			if (isset($_POST['delete'])) {
				$aid = mysql_real_escape_string($_POST['activity']);
				deleteActivity($aid);
			} else if (isset($_POST['change']) && isset($_POST['new_name'])) {
				$name = mysql_real_escape_string($_POST['new_name']);
				
				if (trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>All Activity names must be letters and numbers only!</p>"; 
				} else {
					$aid = mysql_real_escape_string($_POST['activity']);
					changeActivityTitle($aid, $name);
					
					echo "<p>Update was successfully made!</p>";
				}
			}
			
			echo "<div>";	
			echo "<form class='edit' method='post' action='editActivity.php'>";		
			
			echo 'Edit Activity: <select name="activity">';
			$r3 = query("SELECT * from Activities");
  			while ($a = $r3 -> fetch_assoc()){
  				$id = $a['Activity_ID'];
  				$name = $a['Name'];
  				echo "<option value='$id'>$name</option>";
  			}
  	   		echo '</select></br>';

			echo "Change Name: <input type='text' name='new_name'/></br>";
		
			echo "<input class='btn' type='submit' name='change' value='Update Activity' />";
			echo "<input class='btn' type='submit' name='delete' value='Delete Activity' />";
			
			echo "</form>";
			echo "</div>";
		?>
		</div>
	</div>
</div>
</body>
</html>
