<?php
	require('includes/header.php');
	if(isset($_SESSION['logged']) && !empty($_SESSION['logged']))
		header('location:ViewFile.php');
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
			<h1>File</h1>
			<?php
			
			//header('location:ViewFile.php');
			?>
		</div>
	</div>
</div>
</body>
</html>
