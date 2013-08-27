<?php
	require('includes/header.php');
	//TODO: check if the person is an admin 
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

				
				printf("<h1>Edit/Delete Blog</h1>");
				include('editBlogForm.php');	
			?>
		</div>
	</div>
</div>
</body>
</html>
