<form action="EditProfile.php" method="post" >
	<table>
		<tr>
			<td>New Password: </td>
			<td><input name="newpassword" type="password" value="" class="password" /><span class="passwordWarning"></span></td>
		</tr>
		<tr>
			<td>Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		<tr>
			<td>Email Address: </td>
			<td><input name="emailaddress" type="text" value="" class="emailAddress" /><span class="emailAddressWarning"></span></td>
		</tr>
		<tr>
			<td>Request Permission Change:</td>
			<td>
			
			<?php
			$userid = $_SESSION['logged'];
			$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
			if ($mysqli->errno) {
				print($mysqli->error);
				exit();
			}
			$user = $mysqli->query("SELECT * FROM Users WHERE User_ID = '$userid'");
			if($mysqli->errno) {
				print($mysqli->error);
				exit();	
			}
			$result = $user->fetch_assoc();
			$levels = array('New Member','Silkroad Member', 'Chapter President', 'Admin');
			$x = 1;
			foreach($levels as $item) {
				if($x != $result['Permission']) {
					print("<INPUT type='radio' name='permission' value='".$x."' />".$item."&nbsp;");
				}
				else {
					print("<INPUT type='radio' name='permission' value='".$x."' disabled />".$item."&nbsp;(Current)&nbsp;");
				}
				$x++;
			}
			
			?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>Please enter your original password below:</td>
		</tr>
		<tr>
			<td>Old Password: </td>
			<td><input name="oldpassword" type="password" value="" class="password2" /><span class="passwordWarning2"></span></td>
		</tr>			
		<tr>
			<td colspan="2"><input type="submit" class="btn btn-primary" name="submit" value="Submit" /></td>
		</tr>
	</table>
</form>
