<form action="addCountry.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		
		<tr>
			<td>Region: </td>
	      	<td>
	      		<select name="region">
	  			<?php
	  				$result = query("SELECT * from Regions");
	  				while ($b = $result -> fetch_assoc()){
	  					$bid = $b['Region_ID'];
	  					$bname = $b['Name'];
	  					echo "<option value='$bid'>$bname</option>";
	  				}
	  			?>
	  	   		</select>
	  	   	</td>
		</tr>
		
		<tr>
			<td colspan="2"><input id="upload" type="submit" class="btn btn-primary" name="button" value="Submit" /></td>
		</tr>
	</table>
</form>
