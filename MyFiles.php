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
				include('memberCheck.php');
			?>
			<h1>My Files</h1>
			Sort By: <div class="btn-group">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
	<?php if(isset($_GET['sort']) && !empty($_GET['sort'])) echo $_GET['sort']; ?>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a tabindex="-1" href="MyFiles.php?sort=Oldest">Oldest</a></li>
    <li><a tabindex="-1" href="MyFiles.php?sort=MostRecent">Most Recent</a></li>
    <li><a tabindex="-1" href="MyFiles.php?sort=User">User</a></li>
    <li><a tabindex="-1" href="MyFiles.php?sort=FileName">File Name</a></li>
    <li><a tabindex="-1" href="MyFiles.php?sort=Type">File Type</a></li>
  </ul>
</div>
			
			<table class="table table-hover" cellpadding="5" cellspacing="5">
			<tr><th>#</th><th>File Name</th><th>Type</th><th>Uploaded Date</th><th>Caption</th><th>Download</th><th>Delete</th></tr>
			<?php
			
			//if delete form is set
			if(isset($_GET['delete']) && !empty($_GET['delete'])) {
				$fid = $_GET['delete'];
				//get the file information from the Files table Joined with Users table
				$file = $mysqli->query("SELECT *, Files.Name as fName, Users.Name as uName FROM Files JOIN Users USING(User_ID) WHERE File_ID = '$fid'");
				//if there's any error, display the error message
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//else, fetch the row
				$result = $file->fetch_assoc();
					//check if the logged in user matches with the file uploader's information
						//of if the logged in user has an admin permission
					//if not, tell the user that he/she does not have permission to delete the file
				$userid = $_SESSION['logged'];
				$userquery = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
				//if there's an error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				$user = $userquery->fetch_assoc();
				$userperm = $user['Permission'];
				if($result['User_ID'] == $userid || $userperm == 4) { 
					//DELETE FILE
					$file = $mysqli->query("DELETE FROM Files WHERE File_ID = '$fid'");
					//if there's any error, display the error message
					if($mysqli->errno) {
						print($mysqli->error);
						exit();	
					}
					//delete the file from the Files table
					echo "<p>succesfully deleted this file</p>";
				}
				else {
					echo "<p>You don't have permission to delete this file.</p>";
					
				}
			}
			//whether or not delete form is set, display all the files

			$query = "SELECT *, Files.Name as fName, Users.Name as uName FROM Files JOIN Users USING(User_ID)";
			//retrieve all information from the Users table
			if(isset($_GET['sort']) && !empty($_GET['sort'])) {
				$sortBy = $_GET['sort'];
				if($sortBy == 'FileName')
					$query .= " ORDER BY fName";
				if($sortBy == 'Type')
					$query .= " ORDER BY Type";
				if($sortBy == 'Oldest')
					$query .= " ORDER BY UploadDate";
				if($sortBy == 'MostRecent')
					$query .= " ORDER BY UploadDate DESC";
				if($sortBy == 'User')
					$query .= " ORDER BY uName";
			}
			$file = $mysqli->query($query);
				
				//select all the information from Files table Joined with Users table
				
				//if there's an error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//retrieve the userid from the logged session
				$userid = $_SESSION['logged'];
				$userquery = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
				//if there's an error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				$user = $userquery->fetch_assoc();
				$userperm = $user['Permission'];
				//fetch all the rows and display the information
					//display delete button if the logged in user matches with the file uploader's information
						//of if the logged in user has an admin permission				
				while($result = $file->fetch_assoc()) {
					if($result['User_ID'] == $userid) {
					print("<tr><td>".$result['File_ID']."</td>");
					print("<td>".$result['fName']."</td>");
					print("<td>".$result['Type']."</td>");
					print("<td>".$result['UploadDate']."</td>");
					print("<td>".$result['Caption']."</td>");
					print("<td><a href='".$result['URL']."'>Download</a></td>");
					print("<td>");
					if($result['User_ID'] == $userid || $userperm == 4) {
						if(isset($_GET['sort']) && !empty($_GET['sort'])) {
							print("<a href='MyFiles.php?sort=".$_GET['sort']."&delete=".$result['File_ID']."'>Delete</a>");
						}
						else
							print("<a href='MyFiles.php?delete=".$result['File_ID']."'>Delete</a>");
					
					}
					print("</td></tr>");
					}
				}
			
			?>
			</table>
			
			<?php
			/**
				$file = $mysqli->query("SELECT *, Files.Name as fName, Users.Name as uName FROM Files JOIN Users USING(User_ID)");
				//if there's an error, display the error message and exit
				if($mysqli->errno) {
					print($mysqli->error);
					exit();	
				}
				//retrieve the userid from the logged session
				$userid = $_SESSION['logged'];
				//fetch all the rows and display the information
					//display delete button if the logged in user matches with the file uploader's information
						//of if the logged in user has an admin permission				
				print("<table class='table table-hover' cellpadding='5' cellspacing='5'>");
				print("<tr><th>#</th><th>File Name</th><th>Uploaded Date</th><th>Caption</th><th>Uploader</th><th>Download</th><th>Delete</th></tr>");
				while($result = $file->fetch_assoc()) {
					if($result['User_ID'] == $userid) { // TODO: OR Admin
						print("<tr><td>".$result['File_ID']."</td>");
						print("<td>".$result['fName']."</td>");
						print("<td>".$result['UploadDate']."</td>");
						print("<td>".$result['Caption']."</td>");
						print("<td>".$result['uName']."</td>");
						print("<td><a href='".$result['URL']."'>Download</a></td>");
						print("<td>");
						print("<a href='ViewFile.php?delete=".$result['File_ID']."'>Delete</a>");
						print("</td></tr>");
					}
				}
				print("</table>");
				**/
			?>
			</div>
	</div>
</div>
</body>
</html>
