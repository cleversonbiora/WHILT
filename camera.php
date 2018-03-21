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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, user-scalable=yes">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/main.css" />
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<style>
a#downloadLink {
	display: block;
	margin: 0 0 1em 0;
	min-height: 1.2em;
}
p#data {
	min-height: 6em;
}
</style>
	
</head>
<body style="background-color: rgba(0,0,0,0.7);">
	<div class="container" style="max-width:600px;border:1px solid #d8d8d8;margin:auto;background-color: #fff;border-radius: 6px;">
		<div class="page-header">
		  <h1>Capturar Video</h1>
		</div>
		<div class="row">
			<div class="span12">
				<div class="col-md-12" style="text-align:center;">
					<video width="300" height="240" style="width:300px;height:220px;" id="campreview" autoplay="true" muted></video>
					<video width="300" height="240" style="width:300px;height:220px;display:none;" id="result" controls="controls"></video>
				</div>
			</div>
		</div>
		<div style = "text-align:center;">
			<button id="rec" class="btn btn-sm btn-danger" onclick="onBtnRecordClicked()">Gravar</button>
			<button id="pauseRes" class="btn btn-sm btn-info" onclick="onPauseResumeClicked()" disabled>Pausar</button>
			<button id="stop" class="btn btn-sm btn-success"  onclick="onBtnStopClicked()" disabled>Finalizar</button>
		 </div>
		<div class="row" style="margin:15px;">
		 <form id="formVideo" class="form" role="form" method="post" onsubmit="return insertVideo(this)">
		  <div class="form-group">
			<input type="text" class="form-control" id="nome" name="nome" placeholder="Titulo" data-validation="length" data-validation-length="3-100" data-validation-error-msg="Nome não preenchido.">
		  </div>
		  <div class="form-group">
			<input id="video" name="video" type="hidden"> 
			<input id="grupo_id" name="grupo_id" type="hidden" value="<?php echo $_GET["idGrupo"];?>"> 
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição">
		  </div>
		  <button id="addVideo" type="submit" class="btn btn-success">Adicionar</button>
		  <a class="btn btn-default" href="index.php#grupo<?php echo $_GET["idGrupo"];?>">Voltar</a>
		</form>
		 </div>
		<a id="downloadLink" style="display:none;" download="mediarecorder.webm" name="mediarecorder.webm" href></a>
		<p id="data" style="display:none;" ></p>
		<script src="js/main.js"></script>
	</div>
</body>


</html>
