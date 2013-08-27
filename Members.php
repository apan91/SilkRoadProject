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
			<h1>Members Sitemap</h1>
			<h3>Profile</h3>
			<p>View Profile: View my information and my donations</p>
			<p>Edit Profile: Edit my information and request membership change</p>
			<h3>Message</h3>
			<p>Send group messages to other Silkroad members</p>
			<h3>Downloads (Only available to Silkroad members, Permission > 1)</h3>
			<p>View Files: view all the files uploaded by Silkroad members</p>
			<p>My Files: view files uploaded by me</p>
			<p>Upload File: add a new file</p>
		</div>
	</div>
</div>
</body>
</html>
