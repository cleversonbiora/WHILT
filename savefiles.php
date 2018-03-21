<?php
	
	
	$blob = $_POST['thefile'];
	$filename = $_POST['filename'];

	$post_data = file_get_contents('php://input');

	error_log('the post data is: '.$post_data);
	error_log('the filename is: '.$filename);
	file_put_contents($_GET['path'], $post_data);
?>