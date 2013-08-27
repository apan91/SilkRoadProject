<form action="GalleryFilter.php" method="post" enctype="multipart/form-data">
	<table class="add">
		<tr>
			<td>Country: &nbsp</td>
	      	<td>
	      		<select id="filterCountry" class="filterPhoto">
	  			<?php
	  				echo "<option value='-1'></option>";
	  				$result = query("SELECT * from Countries");
	  				while ($b = $result -> fetch_assoc()){
	  					$bid = $b['Country_ID'];
	  					$bname = $b['Name'];
	  					echo "<option value='$bid'>$bname</option>";
	  				}
	  			?>
	  	   		</select>
	  	   		&nbsp&nbsp&nbsp
	  	   	</td>
	  	   	
			<td>Chapter: &nbsp</td>
	      	<td>
	      		<select id="filterChapter" class="filterPhoto">
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
	  	   		&nbsp&nbsp&nbsp
	  	   	</td>
	  	   	
			<td>Activity: &nbsp</td>
	      	<td>
	      		<select id="filterActivity" class="filterPhoto">
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
	  	   		&nbsp&nbsp&nbsp
	  	   	</td>
		</tr>
		
		<tr>
			<td>Start: </td>
			<td><input class="filterDate" name="date" type="text" value="2008-01-01" data-date-format="yyyy-mm-dd" id="datepickerStart" readonly /></td>
			<td>End: </td>
			<td><input class="filterDate" name="date" type="text" value="" data-date-format="yyyy-mm-dd" id="datepickerEnd" readonly /></td>
		</tr>
	</table>
</form>
