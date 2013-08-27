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
			
			//if PCF (Past,Current,Future) value is set
			if(isset($_GET['PCF']) && !empty($_GET['PCF'])) {
				//get PCF value
				$pcf = $_GET['PCF'];
				print("<h1>".$pcf." Projects</h1><br/>");
				$pcfnum = array('Past'=>1, 'Current'=>2, 'Future'=>3);
				//retrieve all projects from Projects table where PCF matches the PCF value
				$result = $mysqli->query("SELECT * FROM Projects WHERE PCF = '$pcfnum[$pcf]'");
				//if there's any error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//fetch all the rows and display the cover image and the project name
					//link them to a page to view that specific project
				$x = 0;
				while($row = $result->fetch_assoc()) {
					if($x %3 == 0) {
						print("<ul class=\"thumbnails\">");
					}
					print("<li class=\"span3\">");
					print("<h3><a href='Projects.php?PID=".$row['Project_ID']."' >");
					print("<img src='".$row['Cover_URL']."' width='300px' height='auto' />");
					print($row['Name']."</a></h3>");
					if($x %3 == 2) {
						print("</ul>");
					}
					$x++;
				}
				if($x <= 2)
					print("</ul>");
			}
			//if PID value is set (specific project id is set)
			else if(isset($_GET['PID']) && !empty($_GET['PID'])) {
				$pid = $_GET['PID'];
				//retrieve all the information from the Project table that matches the project id
				$result = $mysqli->query("SELECT * FROM Projects WHERE Project_ID = '$pid'");
				//if there's any error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//fetch a row and display all the information for that project
				$row = $result->fetch_assoc();
				print("<h2>".$row['Name']."</h2>");
				print("<div class=\"media\"><img class=\"pull-left media-object\" src='".$row['Cover_URL']."' height='300px' width='300px' />");
				print("<div class=\"media-body\"><p>".$row['Caption']."</p>");
				print("</div></div>");
			}
			//else, (no PCF or no PID specified)
			else {
				//list a link to select Past, Current, or Future projects
				print("<h1>Projects</h1>");
				print("<h3><a href='Projects.php?PCF=Past' >Past</a></h3>");
				print("<h3><a href='Projects.php?PCF=Current' >Current</a></h3>");
				print("<h3><a href='Projects.php?PCF=Future' >Future</a></h3>");
			}
			?>
			</div>
	</div>
</div>
</body>
</html>
