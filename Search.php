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
			<h1>SilkRoad Search</h1><br>
			<form class="form-search" action="Search.php" method="post">
			Search Term:
			<div class="input-append">
			<input type="text" class="input-large search-query" name="search"/>
			<button class="btn btn-primary" type="submit" name="submit">Search</button>
			</div>
			
			</form>
			<?php
				if (isset($_POST['search'])){
					
					$term = strip_tags(htmlentities($_POST['search']));
					if (preg_match("/^[0-9A-Za-z ]+$/", $term)){
						
						echo "<h2>Search Project</h2>";
						searchProject($term);
						
						echo "<br><h2>Search Donation</h2>";
						searchDonation($term);
						
						if((isset($_SESSION['logged']) && !empty($_SESSION['logged']))) {

							//retrieve the userid from the logged session
							$userid = $_SESSION['logged'];
							$userquery = query("SELECT * FROM Users WHERE User_ID = '$userid'");
							$user = $userquery->fetch_assoc();
							$userperm = $user['Permission'];
							
							if($userperm >= 2) {
								echo "<br><h2>Search File</h2>";
								searchFile($term);
							}
						}
						
						echo "<br><h2>Search Blog</h2>";
						generateBlogList($term);
						
						echo "<br><h2>Search Photos</h2>";
						generateSearchTable2($term);
						
					} else {
						echo "Invalid search term!";
					}
					
				}
			?>
			
		</div>
	</div>
</div>
</body>
</html>
