<?php 
include_once "conexao.php";
class Video { 
    public $id; 
    public $nome;
    public $descricao;
    public $grupo_id;
    public $usuario_id;
    public $grupoUser_id;
    public $tags;
    public $dataCadastro;
    public $curtida;
    public $url;
    public $usuario;
	
    public static function cadastrar($nome,$descricao,$url,$grupo_id,$usuario_id,$tags) { 
		$conn = MySql::conectar();
        $sql = "INSERT INTO oq_video (nome, descricao, url, grupo_id, usuario_id, tags,dataCadastro,curtida,deletado)
			VALUES ('" . $nome . "', '" . $descricao . "', '" . $url . "'," . $grupo_id . ", " . $usuario_id . ", '" . $tags . "', NOW(),0,0)";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    public static function remover($id, $usuario_id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_video SET grupo_id = 0 where id = " . $id . " and (usuario_id = " . $usuario_id . " or grupo_id in(select id from oq_grupo WHERE usuario_id = " . $usuario_id . "))";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
    public static function deletar($id, $usuario_id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_video SET deletado = 1 where id = " . $id . " and usuario_id = " . $usuario_id . "";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    }
	
    public static function getId($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT v.*, u.nome As usuario FROM oq_video v INNER JOIN oq_usuario u on u.id = v.usuario_id where v.id = " . $id;
		$obj = new Video();
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->tags = $g["tags"];
				$obj->dataCadastro = date_format(date_create($g["dataCadastro"]), 'd/m/Y H:i');
				$obj->curtida = $g["curtida"];
				$obj->url = str_replace("http://", "https://", $g["url"]);
				$obj->usuario = $g["usuario"];
			}
		}
		mysqli_close($conn);
		return $obj;
    } 
	
    public static function getUsuario($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT v.*, u.nome As usuario FROM oq_video v INNER JOIN oq_usuario u on u.id = v.usuario_id where v.deletado = 0 and v.usuario_id = " . $id;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Video();
				$string = $g["url"];
				if (strpos($string, 'facebook') !== false){
					$url = "https://www.facebook.com/plugins/video.php?href=" . urlencode($string);
				}else{
					//$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
					$search     = '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
					$replace    = "https://youtube.com/embed/$2";    
					$url = preg_replace($search,$replace,$string);
				}
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->tags = $g["tags"];
				$obj->dataCadastro = date_format(date_create($g["dataCadastro"]), 'd/m/Y H:i');
				$obj->curtida = $g["curtida"];
				$obj->url = str_replace("http://", "https://", $url);
				$obj->usuario = $g["usuario"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
    public static function getGrupo($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT v.*, u.nome As usuario, g.usuario_id As grupoUser_id FROM oq_video v INNER JOIN oq_usuario u on u.id = v.usuario_id INNER JOIN oq_grupo g on g.id = v.grupo_id where v.deletado = 0 and v.grupo_id = " . $id;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Video();
				$string = $g["url"];
				if (strpos($string, 'facebook') !== false){
					$url = "https://www.facebook.com/plugins/video.php?href=" . urlencode($string);
				}else{
					//$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
					$search     = '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
					$replace    = "https://youtube.com/embed/$2";    
					$url = preg_replace($search,$replace,$string);
				}
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->grupoUser_id = $g["grupoUser_id"];
				$obj->tags = $g["tags"];
				$obj->dataCadastro = date_format(date_create($g["dataCadastro"]), 'd/m/Y H:i');
				$obj->curtida = $g["curtida"];
				$obj->url = str_replace("http://", "https://", $url);
				$obj->usuario = $g["usuario"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
    public static function getUsuarioPage($id,$page) { 
		$qtd = 12;
		$offset = ($page -1) * $qtd; 
		$conn = MySql::conectar();
        $sql = "SELECT v.*, u.nome As usuario FROM oq_video v INNER JOIN oq_usuario u on u.id = v.usuario_id where v.deletado = 0 and v.usuario_id = " . $id . " order by v.id desc LIMIT " . $qtd . " OFFSET " . $offset;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Video();
				$string = $g["url"];
				if (strpos($string, 'facebook') !== false){
					$url = "https://www.facebook.com/plugins/video.php?href=" . urlencode($string);
				}else{
					//$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
					$search     = '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
					$replace    = "https://youtube.com/embed/$2";    
					$url = preg_replace($search,$replace,$string);
				}
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->tags = $g["tags"];
				$obj->dataCadastro = date_format(date_create($g["dataCadastro"]), 'd/m/Y H:i');
				$obj->curtida = $g["curtida"];
				$obj->url = str_replace("http://", "https://", $url);
				$obj->usuario = $g["usuario"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
    public static function getGrupoPage($id,$page) { 
		$qtd = 12;
		$offset = ($page -1) * $qtd; 
		$conn = MySql::conectar();
        $sql = "SELECT v.*, u.nome As usuario, g.usuario_id As grupoUser_id FROM oq_video v INNER JOIN oq_usuario u on u.id = v.usuario_id INNER JOIN oq_grupo g on g.id = v.grupo_id where v.deletado = 0 and v.grupo_id = " . $id . " order by v.id desc LIMIT " . $qtd . " OFFSET " . $offset;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Video();
				$string = $g["url"];
				if (strpos($string, 'facebook') !== false){
					$url = "https://www.facebook.com/plugins/video.php?href=" . urlencode($string);
				}else{
					//$search     = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
					$search     = '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
					$replace    = "https://youtube.com/embed/$2";    
					$url = preg_replace($search,$replace,$string);
				}
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->grupoUser_id = $g["grupoUser_id"];
				$obj->tags = $g["tags"];
				$obj->dataCadastro = date_format(date_create($g["dataCadastro"]), 'd/m/Y H:i');
				$obj->curtida = $g["curtida"];
				$obj->url = str_replace("http://", "https://", $url);
				$obj->usuario = $g["usuario"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
	
    public static function getQtdeTotal() {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_video v inner join oq_grupo g on g.id = v.grupo_id where v.deletado = 0 and g.deletado = 0";
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
    public static function getQtde($empresaId) {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_video v inner join oq_grupo g on g.id = v.grupo_id where v.deletado = 0 and g.deletado = 0 and g.empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
} 
?>