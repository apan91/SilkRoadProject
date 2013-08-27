<?php
	if (!isset($_SESSION))
		session_start();
	$mysqli = new mysqli("localhost", "Jirex", "xek5hsh7vhk", "info230_SP13FP_Jirex");
	if ($mysqli->errno) {
		print($mysqli->error);
		exit();
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
  	<?php
  		include 'includes/MyTools.php'; 
  	?>
  	
    <title>Silkroad by JIREX</title>
    <link href='http://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <link href='lightbox/css/lightbox.css' rel='stylesheet' />
	<script src='lightbox/js/jquery-1.7.2.min.js'></script>
	<script src='lightbox/js/lightbox.js'></script>
    
   	<script type="text/javascript" src="jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
	<link href="jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" />
    
    <link href='includes/bootstrap.css' rel='stylesheet' />
    <link href='includes/bootstrap-responsive.css' rel='stylesheet' />
    <script src='js/bootstrap.min.js'></script>
	<link href='includes/bootstrap-fileupload.min.css' rel='stylesheet' />
	<script src='js/bootstrap-fileupload.min.js'></script>

	
	<link href='includes/datepicker.css' rel='stylesheet' />
	
<link href="includes/blogstyles.css" style="text/css" rel="stylesheet">

	<script src='js/scripts.js'></script>
	<link href="includes/styles.css" rel="stylesheet" />
	<script src='js/bootstrap-datepicker.js'></script>
  
    <link rel="stylesheet" href="includes/flexslider.css" type="text/css" />
	<script src="js/jquery.flexslider.js"></script>
	<!--slider-->
	<script type="text/javascript" charset="utf-8">
  $(window).load(function() {
    $('.flexslider').flexslider({pauseOnHover: true});
  });
</script>

  </head>
