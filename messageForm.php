<form action="Message.php" method="post" >
	<table>
		<tr>
			<td>Send To: </td>
			<td>
			<?php
			
			//Show all levels
			print("<select name='sendTo'>");
			$permissions= array('New Member'=>1,'Silkroad Member'=>2, 'Chapter President'=>3, 'Admin'=>4);

			foreach($permissions as $item=>$perm) {
				print("<option value='".$perm."'>".$item."</option>");
			}
			print("</select>");
			?>			
			</td>
		</tr>
		<tr>
			<td>Title: </td>
			<td><input name="title" type="text" value="" /></td>
		</tr>
		<tr>
			<td>Message: </td>
			<td><textarea name="msg" rows="4" cols="50" ></textarea></td>
		</tr>
			
		<tr>
			<td colspan="2"><input type="submit" class="btn btn-primary" name="submit" value="Submit" /></td>
		</tr>
	
	</table>
</form>
