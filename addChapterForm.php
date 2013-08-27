<form action="addChapter.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		
		<tr>
			<td>Image File: </td>
			<td><div class="fileupload fileupload-new" data-provides="fileupload"><div class="input-append">
					<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
						<span class="fileupload-preview"></span>
					</div>
					<span class="btn btn-file"><span class="fileupload-new">Select file</span>
					<span class="fileupload-exists">Change</span><input name="uploadedfile" accept="image/jpeg" type="file" /></span>
					<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					<span class="badge badge-info">Acceptable File Types are JPG and JPEG Only</span>
				</div></div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><input id="upload" type="submit" class="btn btn-primary" name="button" value="Submit" /></td>
		</tr>
	</table>
</form>
