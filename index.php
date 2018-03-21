<?php
	session_start();
	include_once("classes/empresa.php");
	include_once("classes/usuario.php");
	if (strpos($uri, 'login.php') == false) {
		$uri = $_SERVER['REQUEST_URI'];
		$uris = explode("/",$uri);
		$empresaUrl = $uris[1];
		if($empresaUrl == "app"){
			$empresaId = 0;
		}else{
			$empresaId = Empresa::getUrl($empresaUrl);
			//echo $empresaId;
			//$c = Empresa::getId($empresaId);
			$c = Empresa::getId($empresaId);
			if($c->ativo == 0){
				header('Location: '.'indisponivel.php');
				exit();
			}
		}
		if(!isset($_SESSION["user_".$empresaUrl]) || $_SESSION["user_".$empresaUrl] == 0){
				header('Location: '.'login.php');
				exit();
		}
	}
	
	$user = Usuario::getId($_SESSION["user_".$empresaUrl]); 
	if($user->deletado == 1){
		header('Location: '.'logout.php');
		exit();
	}
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <? 
		include_once("classes/grupo.php");
		include("header.php");
	?>
	<style>
		iframe{
			overflow: hidden !important;
		}
		video::-internal-media-controls-download-button {
			display:none;
		}

		video::-webkit-media-controls-enclosure {
			overflow:hidden;
		}

		video::-webkit-media-controls-panel {
			width: calc(100% + 30px); /* Adjust as needed */
		}
		@media screen and (max-width: 768px) {
			.embed-responsive-16by9 {
				padding-bottom: 80.25%;
			}
			.itemVideo {
				padding: 5px !Important;
			}
			#containerVideo {
				padding: 5px !Important;
			}
			.outer {
				padding: 0px !Important;
				border-radius: 0px;
			}
			html,body {
				margin: 0 0;
				height: 100%;
				width: 100%;
				overflow: auto;
				-webkit-overflow-scrolling: touch;
			}
			.desktop{
				display:none !important;
			}
			.panel-left{
				width: 100%;
				height: 100%;
			}
			.panel-right{
				width: 100%;
				height: 100%;
			}
			.topoRight{
				border-radius: 0px 0px 0 0;
			}
			.topoLeft{
				border-radius: 0px 0px 0 0;
			}
			
			.inner{
				border-radius: 0px !Important;
			}
			.itemVideo {
				height: 255px;
			}
		}
		@media screen and (min-width: 769px) {
			.mobile{
				display:none !important;
			}
			
			.itemVideo{
				margin-bottom:15px;
				height: 360px;
				overflow: hidden;
			}
			.panel-left{
				width: 30%;
				height: 100%;
				float:left;
				border-right: 1px solid #dcdcdc;
			}
			.panel-right{
				width: 70%;
				height: 100%;
				float:left;
			}
			.topoRight{
				border-radius: 0 10px 0 0;
			}
			.topoLeft{
				border-radius: 10px 0 0 0;
			}
		}
		html, body {
			height: 100%;
			width: 100%;
			padding: 0;
			margin: 0;
		}
		.outer{
			height: 100%;
			overflow: hidden;
			padding: 20px;
		}
		.inner{
			width: 100%;
			height: 100%;
			border: 1px solid #dcdcdc;
			border-radius: 10px;
		}
		.itemVideo{
			margin-bottom:15px;
			overflow: hidden;
		}
		.topo{
			background: #7688a0;
			width:100%;
			height:70px;
			color:#FFF;
		}
		span.input-icon, span.input-help {
			display: block;
			position: relative;
		}
		.input-icon > input {
			padding-left: 25px;
			padding-right: 6px;
		}
		.input-icon.input-icon-right > input {
			padding-left: 6px;
			padding-right: 25px;
		}
		span.input-help > input {
			padding-left: 30px;
			padding-right: 6px;
		}
		.input-icon > [class*="fa-"], .input-icon > [class*="clip-"] {
			bottom: 0;
			color: #909090;
			display: inline-block;
			font-size: 14px;
			left: 5px;
			line-height: 35px;
			padding: 0 3px;
			position: absolute;
			top: 0;
			z-index: 2;
		}

		.input-icon.input-icon-right > [class*="fa-"], .input-icon.input-icon-right > [class*="clip-"] {
			left: auto;
			right: 4px;
		}
		.input-icon > input:focus + [class*="fa-"], .input-icon > input:focus + [class*="clip-"] {
			color: #557799;
		}
		.help-button {
			background-color: #65BCDA;
			border-radius: 100% 100% 100% 100%;
			color: #FFFFFF;
			cursor: default;
			position: absolute;
			font-size: 14px;
			font-weight: bold;
			height: 20px;
			padding: 0;
			text-align: center;
			width: 20px;
			line-height: 20px;
			top: 7px;
			left: 7px;
		}
		#listGrupo>li {
			display: block;
			list-style: none;
			padding: 10px;
			border-bottom: 1px solid #dcdcdc;
			cursor: pointer;
		}
		#listGrupo>li:hover {
			background: #e8e8e8;
		}
		#listGrupo {
			padding: 0px;
		}
		.titleVideo {
			color: #7688a0;
		}
		.spanQtdVideo {
			display: block;
			float: right;
		}
		.itemVideo .titleVideo {
			margin: 3px 0px;
		}
		.usuarioVideo {
			font-size: 11px;
			font-weight: bold;
		}
		.dataVideo span{
			    font-size: 10px;
		}
		#optGrupo {
			font-size: 12px;
		}
		#spanParticipantes {
			font-size: 12px;
			position: absolute;
			top: 10px;
			right: 10px;
		}
		.popover-content {
			color: #333;
		}
		#spanCodigo {
			color: #7688a0;
			font-size: 24px;
			font-weight: bold;
			text-align: center;
			width: 100%;
			display: block;
		}
		.popover-content {
			width: 240px;
		}
		#listVideo:after {
			content: "";
			clear: both;
			display: table;
		}
		.selecionado {
			background: #e8e8e8;
		}
	</style>
  </head>
  <body id="page-top" class="index">
	<? include("topo.php"); ?>
	<div class="outer">
		<div class="inner">
			<div class="panel-left">
				<div class="topo topoLeft" style="">
					<div class="col-md-12" style="padding-top: 10px;">
						<a title="Alterar Avatar" data-toggle="modal" data-target="#modalAvatar">
							<?if(strlen($user->avatar) > 0){?>
								<img id="imgAvatar" class="img-circle" style="width: 50px;display: block; float: left;margin-right: 10px;" src="https://rede.social/Imagem/Resize?path=<? echo $user->avatar?>&width=50&height=50" >
							<?}else if(strlen($user->facebook) > 0){?>
								<img id="imgAvatar" class="img-circle" style="width: 50px;display: block; float: left;margin-right: 10px;" src="https://graph.facebook.com/<?echo $user->facebook?>/picture" >
							<?}else{?>
								<img id="imgAvatar" class="img-circle" style="width: 50px;display: block; float: left;margin-right: 10px;" src="images/user.png" >
							<?}?>
						</a>
						<a title="Alterar seu Perfil" data-toggle="modal" data-target="#modalPerfil" style="line-height: 50px;color:#FFFFFF;text-decoration:none;cursor:pointer;">
						<?if(strlen($user->nome) > 0){?>
							<span id="spanNome" class="spanTitulo"><?echo $user->nome?></span>
						<?}else{?>
							<span id="spanNome" class="spanTitulo"><?echo $user->email?>&nbsp;<i class="fa fa-exclamation-triangle" style="color: #ffd60a;"></i></span>
						<?}?>
						</a>
						<div class="dropdown" style="position: absolute;top: 10px;right: 10px;font-size: 20px;color:#fff;">
						  <a data-toggle="dropdown" style="color:#fff;padding: 10px;cursor: pointer;"><i class="fa fa-ellipsis-v"></i></a>
						  <ul class="dropdown-menu dropdown-menu-right">
							<li><a onclick="atualizarMeusVideos(1)">Meus Videos</a></li>
							<li><a data-toggle="modal" data-target="#modalPerfil">Atualizar Dados</a></li>
							<li><a data-toggle="modal" data-target="#modalAvatar">Alterar Avatar</a></li>
							<li><a data-toggle="modal" data-target="#modalSenha">Alterar Senha</a></li>
							<li><a  href="logout.php">Sair</a></li>
						  </ul>
						</div>
						<input type="hidden" id="novo" value="0">
						<input type="hidden" id="usuario_id" value="<?echo $user->id?>">
						<input type="hidden" id="empresa_id" value="<?echo $empresaId?>">
						<input type="hidden" id="msgDelete" value="Deseja realmente remover este video do grupo?\nEsta vídeo ainda estará disponivel, em Meus Videos. ">
					</div>
				</div>
				<div id="divListaGrupo">
					<div class="col-md-12" style="padding: 10px;">
						<span class="spanGrupo"><i class="fa fa-users"></i>&nbsp;Grupos</span>
						<?if($user->tipo<3 || $empresaId == 0){
						if(strlen($user->nome) > 0){?>
							<button type="button" style="float:right;" title="Criar Grupo" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalCriar"><i class="fa fa-plus"></i></button>	
						<?}else{?>
							<button type="button" style="float:right;" title="Criar Grupo" class="btn btn-success btn-xs" onclick="alert('Conclua seu cadastro clicando em seu email para criar grupos!');"><i class="fa fa-plus"></i></button>	
						<?}
						}?>
						<button type="button" style="float:right;margin-right:5px;" title="Entrar no Grupo" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalAdd" ><i class="fa fa-share"></i></button>
						
						<div style="padding: 10px 0px;">
							<span class="input-icon">
								<input type="text" placeholder="Buscar Grupo" id="searchGrupo" class="form-control">
								<i class="fa fa-search"></i> 
							</span>
						</div>
					</div>
					<div class="col-md-12" id="divInnerList" style="padding:0px;overflow: hidden;overflow-y: scroll;">
						<input type="hidden" id="page" value="1">
						<input type="hidden" id="onLoad" value="0">
						<ul id="listGrupo">
						</ul>
						<div id="load" style="text-align: center;display:none;">
							<i class="fa fa-spinner fa-spin" style="text-align: center;"></i>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div class="panel-right desktop">
				<div id="panelVideo" style="display:none;">
					<div class="topo topoRight">
						<div id="topoGrupo" class="col-md-12" style="padding-top: 10px;display:none;">
							<a href="javascript:;" style="color:#fff;" class="mobile" onclick="voltar()"><i class="fa fa-chevron-left fa-lg"></i>&nbsp;&nbsp;</a><span id="titleGrupo"></span>&nbsp;&nbsp;<button id="btnShare" type="button" title="Compartilhar Grupo" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalShare" ><i class="fa fa-share-alt"></i></button><br><span id="optGrupo"></span>
							<span style="cursor:pointer;" id="spanParticipantes"></span>
							<button id="btnSairGrupo" type="button" title="Sair do Grupo" style="float:right;display:none;" class="btn btn-danger btn-sm" ><i class="fa fa-times"></i></button>
							<button id="btnDesativarGrupo" type="button" title="Desativar Grupo" style="float:right;display:none;" class="btn btn-danger btn-sm" ><i class="fa fa-times"></i></button>
							<?if(strlen($user->nome) > 0){?>
								<button id="btnVideo" type="button" title="Adicionar Video" style="float:right;margin-right: 10px;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAddVideo" ><i class="fa fa-plus"></i></button>
								<!--<a style="float:right;margin-right: 10px;" id="linkCamera" title="Gravar Video" class="btn btn-success btn-sm desktop"><i class="fa fa-video-camera"></i></a>-->
							<?}else{?>
								<button id="btnVideo" type="button" title="Adicionar Video" style="float:right;margin-right: 10px;" class="btn btn-success btn-sm" onclick="alert('Conclua seu cadastro clicando em seu email para postar videos!');"><i class="fa fa-plus"></i></button>
							<?}?>
						</div>
						<div id="topoVideos" class="col-md-12" style="padding-top: 10px;">
							<a href="javascript:;" style="color:#fff;" class="mobile" onclick="voltar()"><i class="fa fa-chevron-left fa-lg"></i>&nbsp;&nbsp;</a><span id="titleGrupo"></span><br>
							<span>Meus Videos</span>
						</div>
					</div>
					<div class="col-md-12" style="padding: 10px;">
						<span class="spanGrupo"><i class="fa fa-video-camera"></i>&nbsp;Videos</span>
						<div style="padding: 10px 0px;">
							<span class="input-icon">
								<input type="text" placeholder="Buscar Video" id="searchVideo" class="form-control">
								<i class="fa fa-search"></i> 
							</span>
						</div>
					</div>
					<input type="hidden" id="videoPage" value="1">
					<input type="hidden" id="tipoVideo" value="0">
					<div id="containerVideo" class="col-md-12" style="padding:10px;overflow: hidden;overflow-y: scroll;">
						<div id="listVideo">
						</div>
						<div style="clear:both;"></div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		
	</div>
	<?if($user->tipo<3 || $empresaId == 0){?>
	<div class="modal fade" id="modalCriar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Criar Grupo</h4>
				</div>
				<div class="modal-body">
					<form role="form" method="post" onsubmit="return insertGrupo(this)">
					  <input type="hidden" class="form-control" id="cmd" value="insertGrupo" name="cmd" >
					  <div class="form-group">
						<input type="text" class="form-control" placeholder="Nome" id="nome" name="nome" data-validation="length" 
										 data-validation-length="3-500" 
										 data-validation-error-msg="Informe o nome do grupo.">
					  </div>
					  <button type="submit" class="btn btn-success">Criar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?}?>
	<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Entrar no Grupo</h4>
				</div>
				<div class="modal-body">
					<form role="form" method="post" onsubmit="return addGrupo(this)">
					  <input type="hidden" class="form-control" id="cmd" value="addGrupo" name="cmd">
					  <div class="form-group">
						<input type="text" class="form-control" placeholder="Código" id="codigo" name="codigo" data-validation="length" 
										 data-validation-length="7-10" 
										 data-validation-error-msg="Informe o vodigo.">
					  </div>
					  <button type="submit" class="btn btn-success">Entrar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalShare" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Compartilhar Grupo</h4>
				</div>
				<div class="modal-body">
					<p><span id="sharenovo" style="display:none">Você criou um novo grupo. "</span>
						Para compartilhar com outros usuários, você precisa informar a eles o codigo:<br><br>
						<span id="spanCodigo"></span><br>
						Eles precisam informar este código no botão ENTRAR NO GRUPO.
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm btn-default" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalAddVideo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Adicionar Video</h4>
				</div>
				<div class="modal-body">
					<form id="formVideo" class="form" role="form" method="post" onsubmit="return insertVideo(this)">
					  <input type="hidden" class="form-control" id="cmd" value="insertVideo" name="cmd">
						<input type="hidden" class="form-control" id="grupo_id" name="grupo_id" value="0">
					  <div class="form-group">
						<input type="text" class="form-control" id="nome" name="nome" placeholder="Titulo" data-validation="length" data-validation-length="3-100" data-validation-error-msg="Nome não preenchido.">
					  </div>
					  <div class="form-group">
						<input id="video" name="video" type="file" accept="video/mp4"> 
						<!--<input type="text" class="form-control" id="url" name="url" placeholder="Url Video" data-validation="url" data-validation-error-msg="Informe a url do video.">-->
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição">
					  </div>
					  <button type="submit" class="btn btn-success">Adicionar</button>
					</form>
					<div id="divVideoProgress" style="display:none;">
					  <div class="progress">
						<div id="videoProgress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
						  0%
						</div>
					  </div>
					<div>
						<button style="float:right;" type="button" onclick="cancelUpload()" class="btn btn-default">Cancelar</button>
					</div>
					<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Alterar Avatar</h4>
				</div>
				<div class="modal-body">
					<p>Você pode alterar sua foto, clique em escolher arquivo e faça o upload de uma nova foto.</p>
					<form id="formVideo" class="form" role="form" method="post" onsubmit="return alterarAvatar(this)">
					  <input type="hidden" class="form-control" id="cmd" value="alterarAvatar" name="cmd">
					  <div class="form-group">
						<input id="avatar" name="avatar" type="file" accept="image/*"> 
						<!--<input type="text" class="form-control" id="url" name="url" placeholder="Url Video" data-validation="url" data-validation-error-msg="Informe a url do video.">-->
					  </div>
					  <button type="submit" class="btn btn-success">Alterar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Atualizar Dados</h4>
				</div>
				<div class="modal-body">
					  <div class="form-group">
						<input type="text" class="form-control" value="<? echo $user->email?>" id="emailUser" name="emailUser" disabled>
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control" id="nomeUser" maxlength="25" value="<? echo $user->nome?>" name="nomeUser" placeholder="Nome">
					  </div>
					  <button type="button" onclick="atualizarNome($('#nomeUser').val())" class="btn btn-success">Salvar</button>
			</div>
		</div>
	</div>
	</div>
	<div class="modal fade" id="modalSenha" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="calcularGanho-title" class="modal-title">Atualizar Senha</h4>
				</div>
				<div class="modal-body">
					  <div class="form-group">
						<input type="password" class="form-control" id="senhaUser" name="senhaUser" placeholder="Nova Senha">
					  </div>
					  <button type="button" onclick="atualizarSenha($('#senhaUser').val())" class="btn btn-success">Atualizar Senha</button>
			</div>
		</div>
	</div>
	</div>
	<? include("footer.php"); ?>
	<link rel="stylesheet" type="text/css" href="js/jquery.tagsinput.css">
	<script type="text/javascript" src="js/jquery.tagsinput.js"></script>
	<script src="js/inobounce.js"></script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-92101112-1', 'auto');
	  ga('send', 'pageview');

	</script>
    <script>
		$(function() {
			<?php
				if(isset($_SESSION["recover"]) && $_SESSION["recover"] == 1){
						echo "$('#modalSenha').modal('show');";
						$_SESSION["recover"] = null;
				}
				if(isset($_SESSION["first"]) && $_SESSION["first"] == 1){
						echo "$('#modalPerfil').modal('show');";
						$_SESSION["first"] = null;
				}
			?>
			//$('#tags').tagsInput({width:'auto'});
			atualizarGrupos();
			var hash = location.hash.substr(1);
			if(hash.length > 5){
				var grupoId = hash.replace("grupo","");
				atualizarVideos(grupoId);
			}else{
				atualizarMeusVideos(0);
			}
			$("#divInnerList").height($(".panel-left").height() - 165);
			$("#containerVideo").height($(".panel-right").height() - 185);
			$(window).resize(function() {
				$("#divInnerList").height($(".panel-left").height() - 165);
				$("#containerVideo").height($(".panel-right").height() - 185);
            });
			
			$('#searchGrupo').on('input propertychange paste', function () {

                var nome = jQuery(this).val();

                if (nome === '') {
                    $('#listGrupo > li').fadeIn(300);
                }

                //Retira os "li" que nao contem parte do texto digitado.
                var spanNome = $('#listGrupo').find('span[id*="spanNome"]');
                spanNome.filter(function () {
                    return $(this).text().toLowerCase().indexOf(nome.toLowerCase()) === -1;
                }).closest('li[class*="grupo"]').stop().fadeOut(500);

                //Conforme os "li" somem a rolagem deve subir.
                $('#listGrupo').animate({ scrollTop: $('#listGrupo').prop("scrollHeight") - $('#listGrupo').height }, 500);


                //Adicionar os "li" que contem parte do texto digitado.
                spanNome = $('#listGrupo').find('span[id*="spanNome"]');
                spanNome.filter(function () {
                    return $(this).text().toLowerCase().indexOf(nome.toLowerCase()) > -1;
                }).closest('li[class*="grupo"]').stop().fadeIn(300);

            });
			
			$('#searchVideo').on('input propertychange paste', function () {

                var nome = jQuery(this).val();

                if (nome === '') {
                    $('#listVideo > li').fadeIn(300);
                }

                //Retira os "li" que nao contem parte do texto digitado.
                var spanNome = $('#listVideo').find('h4[id*="titleVideo"]');
                spanNome.filter(function () {
                    return $(this).text().toLowerCase().indexOf(nome.toLowerCase()) === -1;
                }).closest('div[class*="itemVideo"]').stop().fadeOut(500);

                //Conforme os "li" somem a rolagem deve subir.
                $('#listVideo').animate({ scrollTop: $('#listVideo').prop("scrollHeight") - $('#listVideo').height }, 500);


                //Adicionar os "li" que contem parte do texto digitado.
                spanNome = $('#listVideo').find('h4[id*="titleVideo"]');
                spanNome.filter(function () {
                    return $(this).text().toLowerCase().indexOf(nome.toLowerCase()) > -1;
                }).closest('div[class*="itemVideo"]').stop().fadeIn(300);

            });
		});
		function confirmar(form) {
			return confirm('Deseja realmente deletar este video?');
		}
		$.validate({
			lang: 'pt'
		});
	</script>
  </body>
</html>