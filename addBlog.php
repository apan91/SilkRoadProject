<?php
	require('includes/header.php');
	//TODO: check if the person is an admin 
		
		if (!isset($_SESSION))
			session_start();

?>
<body>
<div id="container">
	<div id="masthead">
	<?php
		require_once('includes/menu.php');
	?>
	</div>
	<div id="content">
		<div id="content_inner">
			<?php
				include('adminCheck.php');					

				
				printf("<h1>Add A Blog</h1>");
				include_once('addBlogForm.php');	
			?>
		</div>
	</div>
</div>
</body>
</html>
