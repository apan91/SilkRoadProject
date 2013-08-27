
<form action="" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Title: </td>
			<td><input name="title" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		
		<tr>
			<td>Post: </td>
			<td><textarea name="post" class="textNumbers2" rows="4" cols="50"></textarea><span class="textNumbersWarning2"></span></td>
		</tr>
		
		<tr>
			<td>Cover Photo: </td>
			<td><input type="file"  name="blogPhoto" /><span class="textNumbersWarning3"></span></td>
		</tr>
		
		<tr>
			<td>Tags: </td>
			<td><input name="tags" type="text" value="" class="textNumbers4" /><span class="textNumbersWarning4"></span></td>
		</tr>
				
		<tr>
			<td colspan="2"><input id="upload" type="submit" class="btn btn-primary" name="submitBlog" value="Submit" /></td>
		</tr>
		<tr>
			<td colspan="2"><div id="uploadMessage">
			<?php

				require_once 'includes/blogFunctions.php';
				makeBlogInput();
				
			?>
			
			
			
			</div></td>
		</tr>
	</table>
</form>
