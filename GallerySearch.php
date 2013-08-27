<?php
	require('includes/header.php');
?>
<body>
<div id="container">
	<div id="masthead">
	<?php
		require('includes/menu.php');
	?>
	</div>
	<div id="content">
		<div id="content_inner">
			<h1>Gallery Search</h1><br/>
			
			Photo Search: <input id='gallery_search_id' type='text' name="gallery_search" class="textNumbers" /><span class="textNumbersWarning"></span>
			<div id="searchResults"></div>
		</div>
	</div>
</div>
</body>
</html>
