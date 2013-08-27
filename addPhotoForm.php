<form action="addPhoto.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Name: </td>
			<td><input name="name" type="text" value="" class="textNumbers" /><span class="textNumbersWarning"></span></td>
		</tr>
		
		<tr>
			<td>Caption: </td>
			<td><input name="caption" type="text" value="" class="textNumbers2" /><span class="textNumbersWarning2"></span></td>
		</tr>
		
		<tr>
			<td>Date: </td>
			<td><input name="date" type="text" value="" data-date-format="yyyy-mm-dd" id="datepicker" /></td>
		</tr>
		
		<tr>
			<td>Country: </td>
	      	<td>
	      		<select name="country">
	  			<?php
	  				$result = query("SELECT * from Countries");
	  				while ($b = $result -> fetch_assoc()){
	  					$bid = $b['Country_ID'];
	  					$bname = $b['Name'];
	  					echo "<option value='$bid'>$bname</option>";
	  				}
	  			?>
	  	   		</select>
	  	   	</td>
		</tr>
		
		<tr>
			<td>Chapter: </td>
	      	<td>
	      		<select name="chapter">
	  			<?php
	  				echo "<option value='-1'></option>";
	  				$result = query("SELECT * from Chapters");
	  				while ($a = $result -> fetch_assoc()){
	  					$aid = $a['Chapter_ID'];
	  					$aname = $a['Name'];
	  					echo "<option value='$aid'>$aname</option>";
	  				}
	  			?>
	  	   		</select>
	  	   	</td>
		<tr/>
		
		<tr>
			<td>Activity: </td>
	      	<td>
	      		<select name="activity">
	  			<?php
	  				echo "<option value='-1'></option>";
	  				$result = query("SELECT * from Activities");
	  				while ($a = $result -> fetch_assoc()){
	  					$id = $a['Activity_ID'];
	  					$name = $a['Name'];
	  					echo "<option value='$id'>$name</option>";
	  				}
	  			?>
	  	   		</select>
	  	   	</td>
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
