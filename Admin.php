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
			<h1>Admin Sitemap (Available to Admins only, Permission > 3)</h1>
			<h3>Manage Members</h3>
			<p>View members: view members, change memberships to Silkroad Members, Chapter Presidents or Admins</p>
			<p>Pending Requests: Approve membership change requests</p>
			<h3>Manage Donations</h3>
			<p>View Donations: list donations from members/guests</p>
			<p>Add Cause (Same as Change Contents -> Donations)</p>
			<p>Edit Cause (Same as Change Contents -> Donations)</p>
			<h3>Change Contents</h3>
			<p>Add/edit/delete for Projects, Gallery, Donations, Blogs, Files</p>
		</div>
	</div>
</div>
</body>
</html>
