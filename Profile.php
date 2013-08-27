<?php
	require('includes/header.php');
	if(isset($_SESSION['logged']) && !empty($_SESSION['logged']))
		header('location:ViewProfile.php');
	else
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
			<h1>Profile</h1>
			<p></p>
		</div>
	</div>
</div>
</body>
</html>
