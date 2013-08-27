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
			<h1>Are you interested in:</h1><br/>
			<?php
			$pageName = $_SERVER['REQUEST_URI'];
			//$regions = array('Asia', 'Africa', 'America', 'Austrailia');
			/*foreach($regions as $r) {
				if(!(isset($_GET[$r]) && !empty($_GET[$r]))) {
					print("<h1>".$r."</h1>");
					print("<a href='".$pageName."?&".$r."=y'>Yes</a>");
					print("<a href='".$pageName."?&".$r."=n'>No</a>");
					exit();
				}
			}*/
			
			if(!(isset($_GET['leader']) && !empty($_GET['leader']))) {
				print("<h1 class='text-center'>Leadership?</h1><br/>");
				print("<p class='text-center'><a href='".$pageName."?&leader=y'><span class='choice btn btn-large btn-success'>Yes</span></a>");
				print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
				print("<a href='".$pageName."?&leader=n'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
				exit();
			}
			else {
				if($_GET['leader'] == "y"){
					if(!(isset($_GET['start']) && !empty($_GET['start']))) {
						print("<h1 class='text-center'>Starting a new chapter</h1><br/>");
						print("<p class='text-center'><a href='StartChapter.php'><span class='choice btn btn-large btn-success'>Yes</span></a>");
						print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
						print("<a href='".$pageName."?&start=n'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
						exit();
					}
					else {
						if(!(isset($_GET['fund']) && !empty($_GET['fund']))) {					
							print("<h1 class='text-center'>Starting a Fundraiser?</h1><br/>");
							print("<p class='text-center'><a href='".$pageName."?&fund=y'><span class='choice btn btn-large btn-success'>Yes</span></a>");
							print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
							print("<a href='".$pageName."?&fund=n'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
							exit();
						}
						else {
							if($_GET['fund'] == "y") {
								print("<h1 class='text-center'>Are you creative?</h1><br/>");
								print("<p class='text-center'><a href='Contact.php'><span class='choice btn btn-large btn-success'>Yes</span></a>");//new ideas/feedback
								print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
								print("<a href='Fundraising.php'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
								exit();							
							}
							else {
								//just participate
								if(!(isset($_GET['donate']) && !empty($_GET['donate']))) {
									print("<h1 class='text-center'>Donating</h1><br/>");
									print("<p class='text-center'><a href='".$pageName."?&donate=y'><span class='choice btn btn-large btn-success'>Yes</span></a>");
									print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
									print("<a href='".$pageName."?&donate=n'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
									exit();	
								}
								else{
									if($_GET['donate'] == "y"){
										if(!(isset($_GET['children']) && !empty($_GET['children']))) {
											print("<h1 class='text-center'>Helping children?</h1><br/>");
											print("<p class='text-center'><a href='ViewCauses.php?CID=3'><span class='choice btn btn-large btn-success'>Yes</span></a>");
											print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
											print("<a href='".$pageName."?&children=no'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
											exit();
										}							
										else { //no to children
											print("<h1 class='text-center'>Supporting Microfinance projects?</h1><br/>");
											print("<p class='text-center'><a href='ViewCauses.php?CID=2'><span class='choice btn btn-large btn-success'>Yes</span></a>");
											print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
											print("<a href='ViewCauses.php?CID=1'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
											exit();
										}
									}
									else {
										if(!(isset($_GET['join']) && !empty($_GET['join']))) {					
											print("<h1 class='text-center'>Join a Chapter?</h1><br/>");
											print("<p class='text-center'><a href='Chapters.php'><span class='choice btn btn-large btn-success'>Yes</span></a>");
											print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
											print("<a href='Projects.php?PCF=Current'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
											exit();	
										}
									}
								}
							}
						}
					}
				}
				else {
					//just participate
					if(!(isset($_GET['donate']) && !empty($_GET['donate']))) {
						print("<h1 class='text-center'>Donating</h1><br/>");
						print("<p class='text-center'><a href='".$pageName."?&donate=y'><span class='choice btn btn-large btn-success'>Yes</span></a>");
						print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
						print("<a href='".$pageName."?&donate=n'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
						exit();	
					}
					else{
						if($_GET['donate'] == "y"){
							if(!(isset($_GET['children']) && !empty($_GET['children']))) {
								print("<h1 class='text-center'>Helping children?</h1><br/>");
								print("<p class='text-center'><a href='ViewCauses.php?CID=3'><span class='choice btn btn-large btn-success'>Yes</span></a>");
								print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
								print("<a href='".$pageName."?&children=no'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
								exit();
							}							
							else { //no to children
								print("<h1 class='text-center'>Supporting Microfinance projects?</h1><br/>");
								print("<p class='text-center'><a href='ViewCauses.php?CID=2'><span class='choice btn btn-large btn-success'>Yes</span></a>");
								print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
								print("<a href='ViewCauses.php?CID=1'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
								exit();
							}
						}
						else {
							if(!(isset($_GET['join']) && !empty($_GET['join']))) {					
								print("<h1 class='text-center'>Join a Chapter?</h1><br/>");
								print("<p class='text-center'><a href='Chapters.php'><span class='choice btn btn-large btn-success'>Yes</span></a>");
								print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
								print("<a href='Projects.php?PCF=Current'><span class='choice btn btn-large btn-danger'>No</span></a></p>");
								exit();	
							}
						}
					}
				}
			}
			?>
		</div>
	</div>
</div>
</body>
</html>
