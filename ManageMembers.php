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
				//check if the logged in user is an admin
				include('adminCheck.php');					
		

						
			//if submitted an edit form			
			if(isset($_POST['submit']) && !empty($_POST['submit'])) {
				//check if at least one user was selected
				if(!(isset($_POST['userid']) && !empty($_POST['userid']))) {
					echo "<p>Please select a user.<p>";
				}
				else if(!(isset($_POST['perm']) && !empty($_POST['perm']))) {
					echo "<p>Please select a new permission level.<p>";
				}
				else {
					$userid = $_POST['userid'];
					$newperm = $_POST['perm'];
					//change that person's permission level
					$update = $mysqli->query("UPDATE Users SET Permission = '$newperm' WHERE User_ID = '$userid'");
					if($mysqli->errno) {
						print($mysqli->error);
						exit();	
					}
					echo "<p>succesfully updated the user's permission</p>";
				}
			}
			?>
			<h1>Manage Members</h1>
			<table class="table table-hover" cellpadding="5" cellspacing="5">
			<tr><th>#</th><th>Username</th><th>Name</th><th>Email Address</th><th>Level</th><th>Change Permission</th></tr>
			<?php

			//retrieve all information from the Users table
			$user = $mysqli->query("SELECT * FROM Users");
			//if there is any error, display an error message and exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			//fetch all the user information
			while($result = $user->fetch_assoc()) {
				//show a checkbox in the front of each row/user
				//show all the user information (User_ID, Username, Name, Email, and Permission level
				print("<tr><td>".$result['User_ID']."</td>");
				print("<td>".$result['Username']."</td>");
				print("<td>".$result['Name']."</td>");
				print("<td><a href='mailto:".$result['Email']."'>".$result['Email']."</a></td>");
				$levels = array('Invalid','New Member','Silkroad Member', 'Chapter President', 'Admin');
				
				print("<td>".$levels[$result['Permission']]."</td>");
				
				//display a select option listing all the member permissions
				print("<td><form method='post' action='ManageMembers.php'><select name='perm'>");
				$permissions= array('New Member'=>1,'Silkroad Member'=>2, 'Chapter President'=>3, 'Admin'=>4);
	
				foreach($permissions as $item=>$perm) {
					if($perm != $result['Permission'])
						print("<option value='".$perm."'>".$item."</option>");
				}
				print("</select>");
				print("<input type='hidden' name='userid' value='".$result['User_ID']."' />");
				print("<input type='submit' class='btn btn-primary' name='submit' value='Change' /></form></td></tr>");
			}
			?>
			</table>
		</div>
	</div>
</div>
</body>
</html>
