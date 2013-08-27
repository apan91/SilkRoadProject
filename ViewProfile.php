<?php
	require('includes/header.php');
	if(!(isset($_SESSION['logged']) && !empty($_SESSION['logged'])))
		header('location:Login.php');
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
			<h1>My Profile</h1>
			<table cellpadding="5" cellspacing="5">
			<?php
			
			//get the user id from the logged session
			$userid = $_SESSION['logged'];
			//retrieve all the information from Users table where the user id matches
			$user = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			//fetch a row and display all the information
			$result = $user->fetch_assoc();
			print("<tr><td>Username:</td><td>".$result['Username']."</td></tr>");
			print("<tr><td>Name:</td><td>".$result['Name']."</td></tr>");
			print("<tr><td>Email Address:</td><td>".$result['Email']."</td></tr>");
			$levels = array('Invalid', 'New Member','Silkroad Member', 'Chapter President', 'Admin');
			print("<tr><td>Permission:</td><td>".$levels[$result['Permission']]."</td></tr>");		
			?>
			</table>
						<h1>My Donations</h1>
			Sort By: <div class="btn-group">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
	<?php if(isset($_GET['sort']) && !empty($_GET['sort'])) echo $_GET['sort']; ?>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a tabindex="-1" href="ViewProfile.php?sort=Oldest">Oldest</a></li>
    <li><a tabindex="-1" href="ViewProfile.php?sort=MostRecent">Most Recent</a></li>
    <li><a tabindex="-1" href="ViewProfile.php?sort=Cause">Cause</a></li>
    <li><a tabindex="-1" href="ViewProfile.php?sort=LeastAmount">Least Amount</a></li>
    <li><a tabindex="-1" href="ViewProfile.php?sort=MostAmount">Most Amount</a></li>
  </ul>
</div>
			<table class="table table-hover" cellpadding="5" cellspacing="5">
			<tr><th>#</th><th>Cause</th><th>Amount</th><th>Date</th></tr>
			<?php
			$query = "SELECT * FROM Donations LEFT JOIN Causes on Causes.Cause_ID = Donations.Cause_ID WHERE User_ID = '$userid'";
			//retrieve all information from the Users table
			if(isset($_GET['sort']) && !empty($_GET['sort'])) {
				$sortBy = $_GET['sort'];
				if($sortBy == 'MostRecent')
					$query .= " ORDER BY Date DESC";
				if($sortBy == 'Oldest')
					$query .= " ORDER BY Date";
				if($sortBy == 'Cause')
					$query .= " ORDER BY Donations.Cause_ID";
				if($sortBy == 'MostAmount')
					$query .= " ORDER BY Amount DESC";
				if($sortBy == 'LeastAmount')
					$query .= " ORDER BY Amount";
			}
			$data = $mysqli->query($query);
			//if there is any error, display an error message and exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			//fetch all the user information
			while($result = $data->fetch_assoc()) {
				//show a checkbox in the front of each row/user
				//show all the user information (User_ID, Username, Name, Email, and Permission level
				print("<tr><td>".$result['Donation_ID']."</td>");
				print("<td>".$result['Name']."</td>");
				print("<td>".$result['Amount']."</td>");
				print("<td>".$result['Date']."</td>");
			}
			?>
			</table>
			</div>
	</div>
</div>
</body>
</html>
