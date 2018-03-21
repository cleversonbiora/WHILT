<?php
session_start();
	$uri = $_SERVER['REQUEST_URI'];
	$uris = explode("/",$uri);
	$empresaUrl = $uris[1];
	if (strpos($uri, 'login.php') == false) {
		if(!isset($_SESSION["user_".$empresaUrl]) || $_SESSION["user_".$empresaUrl] == 0){
				header('Location: '.'login.php');
				exit();

		}
	}
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include_once "classes/usuario.php";
	include_once "classes/video.php";
	$user = Usuario::getId($_SESSION["user_".$empresaUrl]); 

	$url = "https://whilt.co/app/".$_REQUEST["video"];
	Video::cadastrar($_REQUEST["nome"],$_REQUEST["descricao"],$url,$_REQUEST["grupo_id"],$_SESSION["user_".$empresaUrl],"");
?>