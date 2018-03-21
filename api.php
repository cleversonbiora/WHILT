<?php
	session_start();
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_PARSE);
	//error_reporting(E_ALL);
	include_once "classes/usuario.php";
	include_once "classes/grupo.php";
	include_once "classes/grupoUsuario.php";
	include_once "classes/video.php";
	include_once "classes/acesso.php";
	include_once "classes/empresa.php";
	$uri = $_SERVER['REQUEST_URI'];
	$uris = explode("/",$uri);
	$empresaUrl = $uris[1];
	//echo $empresaUrl;
	if($empresaUrl == "app"){
		$empresaId = 0;
	}else{
		$empresaId = Empresa::getUrl($empresaUrl);
		if($empresaId <=0){
			echo json_encode(null);
			exit();
		}
	}
	//echo $empresaId;
	if(isset($_SESSION["user_" . $empresaUrl])){
		if($_SESSION["user_" . $empresaUrl] == 0){	
			echo json_encode(null);
			exit();
		}else{
			$userId = $_SESSION["user_" . $empresaUrl];
		}
	}else if(isset($_REQUEST["pin"])){
		$userId = Acesso::get($_REQUEST["pin"]);
	}else if(isset($_REQUEST["cmd"]) && $_REQUEST["cmd"] == "login"){
		$userId = 0;
	}
	else{
		echo json_encode(null);
		exit();
	}
	if(isset($_REQUEST["cmd"]) && strlen($_REQUEST["cmd"]) > 0){
		if($_REQUEST["cmd"] == "obterGrupos"){
			header('Content-type: application/json');
			$listGrupo = Grupo::getUsuario($userId);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterGruposPage"){
			header('Content-type: application/json');
			$listGrupo = Grupo::getUsuarioPage($userId,$_REQUEST["page"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterGrupo"){
			header('Content-type: application/json');
			$listGrupo = Grupo::getId($_REQUEST["id"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterUserGrupo"){
			header('Content-type: application/json');
			$listGrupo = Usuario::getGrupo($_REQUEST["id"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "insertGrupo"){
			$g = Grupo::getNome($_REQUEST["nome"],$userId,$empresaId);
			if($g> 0){
				echo $g;
			}else{
				echo Grupo::cadastrar($_REQUEST["nome"],"",$userId,"",$empresaId);
			}
		}else if($_REQUEST["cmd"] == "obterVideos"){
			header('Content-type: application/json');
			$listGrupo = Video::getGrupo($_REQUEST["id"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterMeusVideos"){
			header('Content-type: application/json');
			$listGrupo = Video::getUsuario($userId);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterVideosPage"){
			header('Content-type: application/json');
			$listGrupo = Video::getGrupoPage($_REQUEST["id"],$_REQUEST["page"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "obterMeusVideosPage"){
			header('Content-type: application/json');
			$listGrupo = Video::getUsuarioPage($userId,$_REQUEST["page"]);
			echo json_encode($listGrupo);
		}else if($_REQUEST["cmd"] == "insertVideo"){
			if($_FILES['video']['size'] > 52428800){
				echo "O arquivo deve ter no maximo 50mb";
			}else{
				$uploaddir = 'videos/';
				$extensao = pathinfo ( $_FILES['video']['name'], PATHINFO_EXTENSION );
				$name =  uniqid(rand(), true);
				$uploadfile = $uploaddir . $name . '.'. $extensao;
				$url = "";
				if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadfile)) {
					$url = "https://whilt.co/app/".$uploadfile;
					echo Video::cadastrar($_REQUEST["nome"],$_REQUEST["descricao"],$url,$_REQUEST["grupo_id"],$userId,"");
				}else{
					echo "0";
				}
			}
			
		}else if($_REQUEST["cmd"] == "addGrupo"){
			$g = Grupo::getCodigo($_REQUEST["codigo"],$empresaId);
			GrupoUsuario::cadastrar($userId,$g->id);
			echo $g->id;
		}else if($_REQUEST["cmd"] == "nomeGrupo"){
			/*if($empresaId == 0){
				echo 0;
			}else{*/
				$g = Grupo::getNome($_REQUEST["nome"],$userId,$empresaId);
				echo $g;
			//}
			
		}else if($_REQUEST["cmd"] == "removeVideo"){
			echo Video::remover($_REQUEST["id"],$userId);
		}else if($_REQUEST["cmd"] == "deletarVideo"){
			echo Video::deletar($_REQUEST["id"],$userId);
		}else if($_REQUEST["cmd"] == "sairGrupo"){
			echo GrupoUsuario::sair($_REQUEST["id"],$userId);
		}else if($_REQUEST["cmd"] == "desativarGrupo"){
			echo Grupo::desativar($_REQUEST["id"],$userId);
		}else if($_REQUEST["cmd"] == "alterarNome"){
			echo Usuario::alterarNome($userId,$_REQUEST["nome"]);
		}else if($_REQUEST["cmd"] == "alterarAvatar"){
			$uploaddir = 'avatar/';
			//print_r($_FILES['avatar']);
			$extensao = pathinfo ( $_FILES['avatar']['name'], PATHINFO_EXTENSION );
			$name =  uniqid(rand(), true);
			$uploadfile = $uploaddir . $name . '.'. $extensao;
			$url = "";
			if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
				$url = "https://whilt.co/app/".$uploadfile;
			}
			Usuario::alterarAvatar($userId,$url);
			echo $url;
		}else if($_REQUEST["cmd"] == "alterarSenha"){
			echo Usuario::alterarSenha($userId,$_REQUEST["senha"]);
		}else if($_REQUEST["cmd"] == "login"){
			header('Content-type: application/json');
			$id = Usuario::login($_REQUEST["email"],$_REQUEST["senha"],$empresaId);
			if($id > 0){
				$codigo = Acesso::cadastrar($id);
				echo json_encode($codigo);
			}else{
				$id = Usuario::getEmail($_REQUEST["email"],$empresaId);
				if($id > 0){
					echo json_encode(0);
				}else{
					Usuario::cadastrar($_REQUEST["email"],$_REQUEST["senha"],$empresaId,3,$_REQUEST["facebook"],$_REQUEST["nome"]);
					$id = Usuario::login($_REQUEST["email"],$_REQUEST["senha"],$empresaId);
					$codigo = Acesso::cadastrar($id);
					echo json_encode($codigo);
				}
			}
		}else if($_REQUEST["cmd"] == "obterUsuario"){
			header('Content-type: application/json');
			$user = Usuario::getId($userId);
			echo json_encode($user);
		}
	}
?>