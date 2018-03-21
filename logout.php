<?php
	session_start();
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$uri = $_SERVER['REQUEST_URI'];
	$uris = explode("/",$uri);
	$empresaUrl = $uris[1];
	$_SESSION["user_".$empresaUrl] = 0;
	header('Location: '.'login.php');
?>