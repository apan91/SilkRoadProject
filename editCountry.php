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
			<h1>Edit Country</h1>
			<br/>
		<?php
			if (isset($_POST['change']) && isset($_POST['new_name'])) {
				$name = mysql_real_escape_string($_POST['new_name']);
				
				if (trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>All Country names must be letters and numbers only!</p>"; 
				} else {
					$cid = mysql_real_escape_string($_POST['country']);
					changeCountryTitle($cid, $name);
					
					echo "<p>Update was successfully made!</p>";
				}
			}
		
			echo "<div>";	
			echo "<form class='edit' method='post' action='editCountry.php'>";		
			
			echo 'Edit Country: <select name="country">';
			$r1 = query("SELECT * from Countries");
			while ($b = $r1 -> fetch_assoc()){
				$bid = $b['Country_ID'];
				$bname = $b['Name'];
				echo "<option value='$bid'>$bname</option>";
			}
  	   		echo '</select></br>';

			echo "Change Name: <input type='text' name='new_name'/></br>";
		
			echo "<input class='btn' type='submit' name='change' value='Update Country' />";
			
			echo "</form>";
			echo "</div>";
		?>
		</div>
	</div>
</div>
</body>
</html>
