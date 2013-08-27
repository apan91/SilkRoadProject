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
			<h1>Edit Chapter</h1>
			<br/>
		<?php
			if (isset($_POST['delete'])) {
				$cid = mysql_real_escape_string($_POST['chapter']);
				deleteChapter($cid);
			} else if (isset($_POST['change']) && isset($_POST['new_name'])) {
				$name = mysql_real_escape_string($_POST['new_name']);
				
				if (trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
					echo "<p>All Chapter names must be letters and numbers only!</p>"; 
				} else {
					$cid = mysql_real_escape_string($_POST['chapter']);
					changeChapterTitle($cid, $name);
					
					echo "<p>Update was successfully made!</p>";
				}
			}
			
			echo "<div>";	
			echo "<form class='edit' method='post' action='editChapter.php'>";		
			
			echo 'Edit Chapter: <select name="chapter">';
			$r2 = query("SELECT * from Chapters");
			while ($a = $r2 -> fetch_assoc()){
				$aid = $a['Chapter_ID'];
				$aname = $a['Name'];
				echo "<option value='$aid'>$aname</option>";
			}
  	   		echo '</select></br>';

			echo "Change Name: <input type='text' name='new_name'/></br>";
		
			echo "<input class='btn' type='submit' name='change' value='Update Chapter' />";
			echo "<input class='btn' type='submit' name='delete' value='Delete Chapter' />";
			
			echo "</form>";
			echo "</div>";
		?>
		</div>
	</div>
</div>
</body>
</html>
