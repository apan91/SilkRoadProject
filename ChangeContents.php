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
				//retrieve the userid from the logged session
				include('adminCheck.php');

			if(!isset($_GET['page']) && empty($_GET['page'])) {
			echo "<h1>Change Contents</h1>";
			echo "<p>Select the page you want to edit:</p>
			<h3><a href='ChangeContents.php?page=Projects'>Projects</a></h3>
			<h3><a href='ChangeContents.php?page=Gallery'>Gallery</a></h3>
			<h3><a href='ChangeContents.php?page=Donations'>Donations</a></h3>
			<h3><a href='ChangeContents.php?page=Blog'>Blog</a></h3>
			<h3><a href='ChangeContents.php?page=Files'>Files</a></h3>";
			}
			else {
				$page = $_GET['page'];
				echo "<h1>Change ".$page."</h1>";
				if($page == 'Projects') {
					echo "<h3><a href='addProject.php'>Add A New Project</a></h3>
					<h3><a href='editProject.php'>Edit/Delete Existing Project</a></h3>";
				}
				else if($page == 'Gallery') {
					echo "<h3><a href='addPhoto.php'>Add New Photo</a></h3>";
					echo "<h3><a href='addCountry.php'>Add New Country</a></h3>";
					echo "<h3><a href='addRegion.php'>Add New Region</a></h3>";
					echo "<h3><a href='addChapter.php'>Add New Chapter</a></h3>";
					echo "<h3><a href='addActivity.php'>Add New Activity</a></h3>";
					echo "<h3><a href='Gallery.php'>Edit/Delete Existing Photo</a></h3>";
					echo "<h3><a href='Gallery.php?sort=Region'>Edit Existing Region</a></h3>";
					echo "<h3><a href='editCountry.php'>Edit Existing Country</a></h3>";
					echo "<h3><a href='editChapter.php'>Edit/Delete Existing Chapter</a></h3>";
					echo "<h3><a href='editActivity.php'>Edit/Delete Existing Activity</a></h3>";
				}
				else if($page == 'Donations') {
					echo "<h3><a href='addCause.php'>Add A New Cause</a></h3>
					<h3><a href='editCause.php'>Edit/Delete Existing Cause</a></h3>";
				}
				else if($page == 'Blog') {
					echo "<h3><a href='addBlog.php'>Add A New Blog</a></h3>
					<h3><a href='editBlog.php'>Edit/Delete Existing New Blog</a></h3>";
				}
				else if($page == 'Files') {
					echo "<h3><a href='UploadFile.php'>Add A New File</a></h3>
					<h3><a href='ViewFile.php'>Edit/Delete Existing Files</a></h3>";
				}
				else {
					echo "nothing to edit";
				}
			}
			?>
		</div>
	</div>
</div>
</body>
</html>
