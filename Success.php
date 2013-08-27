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
			
			$uid = $_POST['custom'];
			$txn = $_POST['txn_id'];
			$cid = $_POST['item_number'];
			$amount = $_POST['mc_gross'];
			
			
			$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
			if ($mysqli->errno) {
				print($mysqli->error);
				exit();
			}
			$query ="INSERT INTO Donations(User_ID, Cause_ID, Amount, txn_ID) VALUES 
					('$uid', '$cid', '$amount', '$txn');";
			$result = $mysqli->query($query);
			//if there is an error, exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}

			
			$query ="SELECT * FROM Users WHERE User_ID = '$uid';";
			$result = $mysqli->query($query);
			//if there is an error, exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			$user = $result->fetch_assoc();
			$name = $user['Name'];
			$query ="SELECT * FROM Causes WHERE Cause_ID = '$cid';";
			$result = $mysqli->query($query);
			//if there is an error, exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			$cause = $result->fetch_assoc();
			$donatedTo = $cause['Name'];
			
			$current = $cause['CurrentAmount'];
			$current += $amount;
			$query ="UPDATE Causes SET CurrentAmount = '$current' WHERE Cause_ID = '$cid';";
			$result = $mysqli->query($query);
			//if there is an error, exit
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			
			print("<h1>Thank you, $name!</h1>");
			print("<p>You donated $".$amount." to $donatedTo.</p>");
			print("<p>Your transaction ID is: ".$txn."</p>");
			
			?>
			

			</div>
	</div>
</div>
</body>
</html>
