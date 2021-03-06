<?php
	session_start();
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include_once "../classes/usuario.php";
	include_once "../classes/empresa.php";
	$uri = $_SERVER['REQUEST_URI'];
	$uris = explode("/",$uri);
	$empresaUrl = $uris[1];
	//echo $empresaUrl;
	if($empresaUrl == "app"){
		$empresaId = 0;
	}else{
		$empresaId = Empresa::getUrl($empresaUrl);
		$c = Empresa::getId($empresaId);
	}
	//echo $empresaId;
	$erro = 0;
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST["email"]) && isset($_POST["senha"])){
			if(strlen($_POST["email"]) < 1){
				$erro = 1;
			}else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
				$erro = 2;
			}else if(strlen($_POST["senha"]) < 6){
				$erro = 3;
			}
			else{
				$user_id = Usuario::loginAdmin($_POST["email"],$_POST["senha"],$empresaId);
				if($user_id > 0){
					$_SESSION["userAdmin_".$empresaUrl] = $user_id;
					header('Location: '.'index.php');
					exit();
				}else{
					$erro = 5;
				}
				
			}
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <? include("header.php"); ?>
	<style>
		body {
			padding-top: 90px;
		}
		.panel-login {
			border-color: #ccc;
			-webkit-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
			-moz-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
			box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
		}
		.panel-login>.panel-heading {
			color: #00415d;
			background-color: #fff;
			border-color: #fff;
			text-align:center;
		}
		.panel-login>.panel-heading a{
			text-decoration: none;
			color: #666;
			font-weight: bold;
			font-size: 15px;
			-webkit-transition: all 0.1s linear;
			-moz-transition: all 0.1s linear;
			transition: all 0.1s linear;
		}
		.panel-login>.panel-heading a.active{
			color: #029f5b;
			font-size: 18px;
		}
		.panel-login>.panel-heading hr{
			margin-top: 10px;
			margin-bottom: 0px;
			clear: both;
			border: 0;
			height: 1px;
			background-image: -webkit-linear-gradient(left,rgba(0, 0, 0, 0),rgba(0, 0, 0, 0.15),rgba(0, 0, 0, 0));
			background-image: -moz-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
			background-image: -ms-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
			background-image: -o-linear-gradient(left,rgba(0,0,0,0),rgba(0,0,0,0.15),rgba(0,0,0,0));
		}
		.panel-login input[type="text"],.panel-login input[type="email"],.panel-login input[type="password"] {
			height: 45px;
			border: 1px solid #ddd;
			font-size: 16px;
			-webkit-transition: all 0.1s linear;
			-moz-transition: all 0.1s linear;
			transition: all 0.1s linear;
		}
		.panel-login input:hover,
		.panel-login input:focus {
			outline:none;
			-webkit-box-shadow: none;
			-moz-box-shadow: none;
			box-shadow: none;
			border-color: #ccc;
		}
		.btn-login {
			background-color: #59B2E0;
			outline: none;
			color: #fff;
			font-size: 14px;
			height: auto;
			font-weight: normal;
			padding: 14px 0;
			text-transform: uppercase;
			border-color: #59B2E6;
		}
		.btn-login:hover,
		.btn-login:focus {
			color: #fff;
			background-color: #53A3CD;
			border-color: #53A3CD;
		}
		.forgot-password {
			text-decoration: underline;
			color: #888;
		}
		.forgot-password:hover,
		.forgot-password:focus {
			text-decoration: underline;
			color: #666;
		}

		.btn-register {
			background-color: #1CB94E;
			outline: none;
			color: #fff;
			font-size: 14px;
			height: auto;
			font-weight: normal;
			padding: 14px 0;
			text-transform: uppercase;
			border-color: #1CB94A;
		}
		.btn-register:hover,
		.btn-register:focus {
			color: #fff;
			background-color: #1CA347;
			border-color: #1CA347;
		}
		h1{
			 text-align: center;
		}
	</style>
  </head>
  <body id="page-top" class="index">
	<div class="container">
		<div class="row" style="text-align:center;">
			<?
				if(strlen($c->logo) > 0){?>
					<img width="300" src="<? echo $c->logo?>">
				<?}else{?>
					<h1><? echo $c->nome?> - WHILT</h1>
			<?}?>
		</div>
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<?if($erro == 1){?>
									<div class="alert alert-danger">
									  Email não preenchido.
									</div>
								<?}else if($erro == 2){?>
									<div class="alert alert-danger">
									  Email inválido.
									</div>
								<?}else if($erro == 3){?>
									<div class="alert alert-danger">
									  A senha deve conter no minímo 6 caracteres.
									</div>
								<?}else if($erro == 4){?>
									<div class="alert alert-danger">
									  Falha no cadastro.
									</div>
								<?}else if($erro == 5){?>
									<div class="alert alert-danger">
									  Email ou Senha incorreto.
									</div>
								<?}?>
								<form id="login-form" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="">
									</div>
									<div class="form-group">
										<input type="password" name="senha" id="senha" tabindex="2" class="form-control" placeholder="Senha">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Login">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<? include("footer.php"); ?>
    <script>
		function confirmar(form) {
			return confirm('Deseja realmente deletar este video?');
		}
		$.validate({
			lang: 'pt'
		});
		$(function() {


		});
	</script>
  </body>
</html>