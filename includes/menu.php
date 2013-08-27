<div id="topmenu">
<table class="leftalign"><tr>
<td class="icon"><a href="https://www.facebook.com/CornellSilkRoad?fref=ts" target="_blank" title="Facebook"><img src='includes/facebook-icon.png' width='20px' height='20px' /></a></td>
<td class="icon"><a href="https://twitter.com/silkroadinc" target="_blank" title="Twitter"><img src='includes/twitter-icon.png' width='20px' height='20px' /></a></td>
<td class="tab"><a href="index.php" title="Cornell Silkroad">Silkroad</a></td>
<td class="tab"><a href="http://ricemagazine.weebly.com" target="_blank" title="Rice Magazine">Rice Magazine</a></td>
<td class="tab"><a href="http://www.silkroadinc.org/index.html" target="_blank" title="Old Website">Old Silkroad</a></td>
</tr></table>
<table class="rightalign">
<tr>
<td class="tab"><a href="index.php" title="Home">Home</a></td>
<td class="tab"><a href="Search.php" title="Search">Search</a></td>
<?php
		
	if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])) {
		$userid = $_SESSION['logged'];
		$userquery = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
		//if there's an error, display the error message and exit
		if($mysqli->errno) {
			print($mysqli->error);
			exit();	
		}
		$user = $userquery->fetch_assoc();
		$userperm = $user['Permission'];
		print('<td class="tab"><a href="Members.php" title="Members">Members</a></td>');
		//retrieve the userid from the logged session

		if($userperm >= 4)
			print('<td class="tab"><a href="Admin.php" title="Adimn">Admin</a></td>');
		print('<td class="tab"><a href="Logout.php?logout=true" title="Logout">Logout</a></td>');
	}
	else {
		print('<td class="tab"><a href="Login.php" title="Login">Login</a></td>');
		print('<td class="tab"><a href="Register.php" title="Register">Register</a></td>');
	}
	
?>
</tr></table>
</div>

<div id="menubar">
<div class="logo"><a href="index.php" title="logo">
<img id="logoimg" src="includes/logo.png" name="logo" /></a>
</div>


<?	
//highlight current menu
	$currentpage = basename( $_SERVER['PHP_SELF'] );
	$parts = explode('.', $currentpage);
	$fileName = $parts[0];
	$pageName = $fileName;
	//combine hoverover categories into one
	if($fileName == "SilkRoad" || $fileName == "History" || $fileName == "Mission")
		$fileName = "SilkRoad";
	if($fileName == "Projects" || $fileName == "Past" || $fileName == "Current" || $fileName == "Future")
		$fileName = "Projects";		
	if($fileName == "Profile" || $fileName == "ViewProfile" || $fileName == "EditProfile") {
		$fileName = "Profile";
	}
	if($fileName == "Participate" || $fileName == "Game" || $fileName == "Chapters" || $fileName == "StartChapter" || $fileName == "Fundraising") {
		$fileName = "Participate";
	}
	if($fileName == "Downloads" || $fileName == "ViewFile" || $fileName == "UploadFile" || $fileName == "MyFiles") {
		$fileName = "Downloads";
	}
	if($fileName == "Donations" || $fileName == "ViewCausese") {
		$fileName = "Donations";
	}
	if ($fileName == "ManageMembers" || $fileName == "PendingRequests")
		$fileName = "ManageMembers";
	if ($fileName == "ChangeContents" || $fileName == "addProject" || $fileName == "editProject" || $fileName == "addCause" || $fileName == "editCause"
	|| $fileName == "addPhoto" || $fileName == "addPhoto")
	{
		$fileName = "ChangeContents";
	}
	if ($fileName == "ViewDonations") {
		$fileName = "ManageDonations";
	}
	if($fileName == "Admin" || $fileName == "ManageMembers" || $fileName == "ChangeContents" || $fileName == "ManageDonations") {
		echo "<div class='rightalign'><ul class='menu'>";
		$menuoptions = array("ManageMembers", "ManageDonations", "ChangeContents");
		foreach($menuoptions as $page) {
			echo "<li>";
			if($page == $fileName) 
				echo "<a class='currentpage' href='".$page.".php'>".$page."</a>";
			else
				echo "<a href='".$page.".php'>".$page."</a>";
			if($page == "ManageMembers")
				echo "<ul class='inside'><li><a href='ManageMembers.php'>View Members</a></li>
			<li><a href='PendingRequests.php'>Pending Requests</a></li>
						</ul>";
			else if($page == "ManageDonations")
				echo "<ul class='inside'><li><a href='ViewDonations.php'>View Donations</a></li>
			<li><a href='addCause.php'>Add Causes</a></li>
			<li><a href='editCause.php'>Edit Causes</a></li>
						</ul>";
			else if($page == "ChangeContents")
				echo "<ul class='inside'><li><a href='ChangeContents.php?page=Projects'>Projects</a></li>
			<li><a href='ChangeContents.php?page=Gallery'>Gallery</a></li>
			<li><a href='ChangeContents.php?page=Donations'>Donations</a></li>
			<li><a href='ChangeContents.php?page=Blog'>Blog</a></li>
			<li><a href='ChangeContents.php?page=Files'>Files</a></li>
						</ul>";	
			echo "</li>";
		}
		echo "</ul></div>";
	
	}
	else if($fileName == "Members" || $fileName == "Profile" || $fileName == "Downloads" || $fileName == "Message" || $fileName == "MySilkroad") {
		echo "<div class='rightalign'><ul class='menu'>";
		$menuoptions = array("Profile", "Message", "Downloads");
		//admin only available if the member is an admin.
		//for testing, available to all.
		
		foreach($menuoptions as $page) {
			echo "<li>";
			if($page != "Downloads" || ($page == "Downloads" && $userperm > 1)) {					
				if($page == $fileName) 
					echo "<a class='currentpage' href='".$page.".php'>".$page."</a>";
				else
					echo "<a href='".$page.".php'>".$page."</a>";
				if($page == "Profile")
					echo "<ul class='inside'><li><a href='ViewProfile.php'>View Profile</a></li>
							<li><a href='EditProfile.php'>Edit Profile</a></li>
							</ul>";	
				else if($page == "Downloads")
					echo "<ul class='inside'><li><a href='ViewFile.php'>View Files</a></li>
					<li><a href='MyFiles.php'>My Files</a></li>
							<li><a href='UploadFile.php'>Upload File</a></li>
							</ul>";	
				else if($page == "Message")
					echo "";
			}
		}
		echo "</ul></div>";
	}
	else {
		echo "<div class='rightalign'><ul class='menu'>";
		$menuoptions = array("SilkRoad", "Projects", "Participate", "Gallery", "Donations", "Contact", "Blog");
		
		foreach($menuoptions as $page) {
			echo "<li>";
			if($page == $fileName) 
				echo "<a class='currentpage' href='".$page.".php'>".$page."</a><ul class='inside'>";
			else
				echo "<a href='".$page.".php'>".$page."</a><ul class='inside'>";
			if($page == "SilkRoad")
				echo "<li><a href='History.php'>History</a></li>
						<li><a href='Mission.php'>Mission</a></li>
						</ul>";	
			else if($page == "Projects")
				echo "<li><a href='Projects.php?PCF=Past'>Past</a></li>
						<li><a href='Projects.php?PCF=Current'>Current</a></li>
						<li><a href='Projects.php?PCF=Future'>Future</a></li>
						</ul>";	
			else if($page == "Participate")
				echo "<li><a href='Chapters.php'>Current Chapters</a></li>
						<li><a href='StartChapter.php'>Start Chapter</a></li>
						<li><a href='Fundraising.php'>Fundrasing Ideas</a></li>
						<li><a href='Game.php'>How to Participate?</a></li>						
						</ul>";	
			else if($page == "Gallery")
				echo "<li><a href='Gallery.php?sort=Region'>Regions</a></li>
						<li><a href='Gallery.php'>All Photos</a></li>
						<li><a href='GallerySearch.php'>Search Photos</a></li>
						</ul>";	
			else if($page == "Donations")
				echo "<li><a href='ViewCauses.php?CID=1'>Silkroad</a></li>
						<li><a href='ViewCauses.php?CID=2'>Microfinance</a></li>
						<li><a href='ViewCauses.php?CID=3'>Education</a></li>
						</ul>";	
			else if($page == "Contact")
				echo "<li><a href='FAQ.php'>FAQ</a></li>
						<li><a href='Contact.php'>Feedback</a></li>
						</ul>";		
			else if($page == "Blog")
				echo "<li><a href='Blog.php'>Posts</a></li>
						<li><a href='Authors.php'>Authors</a></li>
						<li><a href='Tags.php'>Tags</a></li>
						</ul>";		
			echo "</li>";
		}
		echo "</ul></div>";
	}
?>


</div>
