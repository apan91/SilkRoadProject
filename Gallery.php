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
			<h1>Gallery</h1><br/>				
				<?php
				
					$sortType = "";
					if(isset($_GET['sort'])) {
						$sortType = mysql_real_escape_string($_GET['sort']);
					}
					
					$rid = -1;
					if(isset($_GET['rid'])) {
						$rid = mysql_real_escape_string($_GET['rid']);
					}
					
					if($sortType == "Region") {
						
						if (isset($_POST['new_region_name']) && isset($_POST['new_region_name'])) {
							$rname = mysql_real_escape_string($_POST['new_region_name']);
							
							if (trim($rname) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $rname)) {
								echo "<p>All Region names must be letters and numbers only!</p>"; 
							} else {
								$rid = mysql_real_escape_string($_POST['region_id']);
								changeRegionTitle($rid, $rname);
							}
						}
						
						displayAllRegions();
					} else {
				
						include('GalleryFilter.php');
						
						//functionality to handle delete or change caption on a photo
						if (isset($_POST['delete'])) {
							$pid = mysql_real_escape_string($_POST['photo_id']);
							deletePhoto($pid);
						} else if (isset($_POST['change']) && isset($_POST['new_caption']) && isset($_POST['new_name'])) {
							$caption = mysql_real_escape_string($_POST['new_caption']);
							$name = mysql_real_escape_string($_POST['new_name']);
							$country = mysql_real_escape_string($_POST['new_country']);
							$chapter = mysql_real_escape_string($_POST['new_chapter']);
							$activity = mysql_real_escape_string($_POST['new_activity']);
							
							if(trim($caption) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $caption)) {
								echo "<p>All Photo captions must be letters and numbers only!</p>"; 
							} else if (trim($name) == '' || !preg_match('/^[0-9a-zA-Z\s]+$/', $name)) {
								echo "<p>All Photo names must be letters and numbers only!</p>"; 
							} else {
								$pid = mysql_real_escape_string($_POST['photo_id']);
								changePhotoCaption($pid, $name, $caption, $country, $chapter, $activity);
							}
						}
					
				?>		
				<div id="searchResults"><?php displayAllPhotos($rid); ?></div>		
				
				<?php } ?>
		</div>
	</div>
</div>
</body>
</html>
