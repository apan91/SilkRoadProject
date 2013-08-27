<form action="editCause.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Select Cause to edit/delete: </td>
			<td>
			<?php
			$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
			if ($mysqli->errno) {
				print($mysqli->error);
				exit();
			}
			$result = $mysqli->query("SELECT Cause_ID, Name FROM Causes");
			if(!$result) {
				print($mysqli->error);
				exit();
			}	
			print("<select name=\"cid\" >");
			while($row = $result->fetch_assoc()) {
				print("<option value=\"".$row['Cause_ID']."\" >".$row['Name']."</option>");
			}
			print("</select>");

			?>
			</td>
		</tr>
		
		<tr>
			<td>New Cause Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		<tr>
			<td>New Desciption: </td>
			<td><textarea name="description" rows="4" cols="50" ></textarea></td>
		</tr>
		<tr>
			<td>Goal Amount: </td>
			<td><div class="input-prepend input-append">
  <span class="add-on">$</span>
  <input name="goalAmount" class="span2 numbers" id="appendedPrependedInput" type="text" />
  <span class="add-on">.00</span><span class="numbersWarning"></span>
</div></td>
		</tr>
				
		<tr>
			<td>Start Date: </td>
	      	<td><input name="startDate" type="text" value="" id="datepicker" /></td>
		</tr>
		<tr>
			<td>End Date: </td>
	      	<td><input name="endDate" type="text" value="" id="datepicker2" /></td>
		</tr>			
			
		<tr>
			<td>New Cover Photo: </td>
			<td>
				<div class="fileupload fileupload-new" data-provides="fileupload"><div class="input-append">
				<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
				<span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span>
				<span class="fileupload-exists">Change</span><input name="uploadedfile" accept="image/jpeg" type="file" /></span>
				<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				<span class="badge badge-info">Acceptable File Types are JPG and JPEG Only</span>
				</div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td><input type="submit" class="btn btn-primary" name="submit" value="Submit" /></td>
			<td><input type="submit" class="btn btn-primary" name="delete" value="Delete" /></td>
		</tr>
	
	</table>
</form>
