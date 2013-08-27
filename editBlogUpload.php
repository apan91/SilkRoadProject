<?php
	
	if (!isset($_SESSION))
		session_start();
	// this file handles file uploads (when uer adds a photo)

	require('includes/header.php');

	require_once 'includes/blogFunctions.php';
	makeChangeBlogInput();
	
?>