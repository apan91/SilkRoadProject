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
			
			<h1>Pending Requests</h1>
			<table class="table table-hover" cellpadding="5" cellspacing="5">
			<tr><th>User ID</th><th>Username</th><th>Name</th><th>Level</th><th>Requested Level</th><th>Requested Time</th><th>Approve</th></tr>
			<?php
			
			if(isset($_GET['approve']) && !empty($_GET['approve'])) {
				$rid = $_GET['approve'];
				$request = $mysqli->query("SELECT * FROM Requests WHERE Request_ID = '$rid'");
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}	
				$result = $request->fetch_assoc();
				$userid = $result['User_ID'];
				$newperm = $result['Requested'];
				$update = $mysqli->query("START TRANSACTION");
				$update = $mysqli->query("UPDATE Users SET Permission = '$newperm' WHERE User_ID = '$userid'");
				if($mysqli->errno) {
					$mysqli->rollback();
					print($mysqli->error);
					exit();	
				}
				$update = $mysqli->query("DELETE FROM Requests WHERE Request_ID = '$rid'");
				if($mysqli->errno) {
					$mysqli->rollback();
					print($mysqli->error);
					exit();	
				}
				$mysqli->commit();
				//TODO : do the above together, use TRANSACTION maybe?
				echo "<p>succesfully updated the user's permission</p>";
			}
			
			$request = $mysqli->query("SELECT * FROM Requests NATURAL JOIN Users ORDER BY Date");
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			
			while($result = $request->fetch_assoc()) {
				print("<tr><td>".$result['User_ID']."</td>");
				print("<td>".$result['Username']."</td>");
				print("<td>".$result['Name']."</td>");
				$levels = array('Invalid', 'New Member','Silkroad Member', 'Chapter President', 'Admin');
				print("<td>".$levels[$result['Permission']]."</td>");
				print("<td>".$levels[$result['Requested']]."</td>");
				print("<td>".$result['Date']."</td>");
				print("<td><a href='PendingRequests.php?approve=".$result['Request_ID']."'>Approve</a></td></tr>");
			}
			
			?>
			</table>
		</div>
	</div>
</div>
</body>
</html>
