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
			
			//if the cause id is not set
			if(!(isset($_GET['CID']) && !empty($_GET['CID']))) {
				//show list of donations by fetching all the info from Causes table
					//show the cover URL and Cause name
					//make them a link to view that specific cause's information
				print("<h1>List of Donations</h1>");
				print("<p class='text-center'>");
				$result = $mysqli->query("SELECT * FROM Causes ORDER BY StartDate");
				if($mysqli->errno) {
						print($mysqli->error);
						exit();	
					}
				$x = 0;
				while($row = $result->fetch_assoc()) {
					if($x %3 == 0) {
						print("<div class=\"thumbnails row\">");
					}
					print("<div class=\"span3\">");
					print("<h3><a href='ViewCauses.php?CID=".$row['Cause_ID']."' >");
					print("<img src='".$row['Cover_URL']."' width='400px' height='auto' /><br/>");
					print($row['Name']."</a></h3></div>");
					if($x %3 == 2) {
						print("</div>");
					}
					$x++;
				}
				print("</p>");
			}
			//else if cause id is specified
			else {
				$cid = $_GET['CID'];
				//fetch information from Causes table with that Cause_ID
				$result = $mysqli->query("SELECT * FROM Causes WHERE Cause_ID = '$cid'");
				//if there's any error, display the error
				if($mysqli->errno) {
						print($mysqli->error);
						exit();	
					}
				$row = $result->fetch_assoc();
				//else, fetch the row and show all the information
				print("<h2>".$row['Name']."</h2>");
				print("<div class='media'><img class='media-object pull-left' src='".$row['Cover_URL']."' height='200px' width='200px'/>");
				print("<div class='media-body'><p>".$row['Description']."</p>");
				print("<p>Ends on ".$row['EndDate']."</p>");
				print("<p>Current: $".$row['CurrentAmount']." / Goal: $".$row['GoalAmount']."</p>");
				print("<div class=\"progress progress-striped\"  style=\"width:50%\">");
				$percent = $row['CurrentAmount']/$row['GoalAmount']*100;
				print("<div class=\"bar\" style=\"width: $percent%;\">".$percent." %</div></div>");
				include('donateButton.php');
				print("</div></div>");
			}
			?>
			

			</div>
	</div>
</div>
</body>
</html>
