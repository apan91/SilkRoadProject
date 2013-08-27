<form action="editBlogUpload.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Title: </td>
			<td>
			<?php
			require_once("includes/blogFunctions.php");
			displayBlogList();
			?>
			</td>
		</tr>
		
		<tr>
			<td>Post: </td>
			<td><textarea name="post" class="textNumbers2" rows="4" cols="50"></textarea><span class="textNumbersWarning2"></span></td>
		</tr>
		
		<tr>
			<td>Url: </td>
			<td><input type="file" name="blogPhoto" /><span class="textNumbersWarning3"></span></td>
		</tr>
		
		<tr>
			<td>Tags: </td>
			<td><input name="tags" type="text" value="" class="something" /><span class="textNumbersWarning4"></span></td>
		</tr>
				
		<tr>
			<td colspan="2"><input id="upload" type="submit" class="btn btn-primary" name="changeBlog" value="Change" /></td>
			<td colspan="2"><input id="upload" type="submit" class="btn btn-primary" name="deleteBlog" value="Delete" /></td>
		</tr>
	</table>
</form>
