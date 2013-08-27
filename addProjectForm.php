<form action="addProject.php" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<td>Project Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		<tr>
			<td>Caption: </td>
			<td><textarea name="caption" rows="4" cols="50" ></textarea></td>
		</tr>
		<tr>
			<td>Group to be in: </td>
	      	<td>
<input type="radio" name="PCF" value="1" />Past&nbsp;
<input type="radio" name="PCF" value="2" />Current&nbsp;
<input type="radio" name="PCF" value="3" />Future&nbsp;
	  	   	</td>
		</tr>
			
		<tr>
			<td>Cover Photo: </td>
			<td><div class="fileupload fileupload-new" data-provides="fileupload"><div class="input-append">
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
			<td colspan="2"><input type="submit" class="btn btn-primary" name="submit" value="Submit" /></td>
		</tr>
	
	</table>
</form>
