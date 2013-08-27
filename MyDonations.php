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
				include('memberCheck.php');
			?>
			<h1>My Donations</h1>
			Sort By: <div class="btn-group">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
	<?php if(isset($_GET['sort']) && !empty($_GET['sort'])) echo $_GET['sort']; ?>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a tabindex="-1" href="MyDonations.php?sort=Oldest">Oldest</a></li>
    <li><a tabindex="-1" href="MyDonations.php?sort=MostRecent">Most Recent</a></li>
    <li><a tabindex="-1" href="MyDonations.php?sort=Cause">Cause</a></li>
    <li><a tabindex="-1" href="MyDonations.php?sort=LeastAmount">Least Amount</a></li>
    <li><a tabindex="-1" href="MyDonations.php?sort=MostAmount">Most Amount</a></li>
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
</body>
</html>
