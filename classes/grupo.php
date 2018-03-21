<?php 
include_once "conexao.php";
class Grupo { 
    public $id; 
    public $nome;
    public $descricao;
    public $codigo;
    public $tags;
    public $usuario_id;
    public $dataCadastro;
    public $ativo;
    public $deletado;
    public $qtdVideo;
    public $qtdUser;
	public $empresa_id;
	
    public static function cadastrar($nome,$descricao,$usuario_id,$tags,$empresaId) {
		$conn = MySql::conectar();
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$codigo = '';
		foreach (array_rand($seed, 7) as $k) $codigo .= $seed[$k];
        $sql = "INSERT INTO oq_grupo (nome, descricao, usuario_id, tags, codigo,dataCadastro,ativo,deletado,empresa_id)
			VALUES ('" . $nome . "', '" . $descricao . "', " . $usuario_id . ",'" . $tags . "', '" . $codigo . "', NOW(),1,0,'.$empresaId.');";
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if ($result = mysqli_query($conn,"SELECT max(id) As id FROM oq_grupo")) {
				if($g = mysqli_fetch_assoc($result)){
					$id = $g["id"]; 
				}
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
	public static function desativar($id, $usuario_id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_grupo SET ativo = 0 where id = " . $id . " and usuario_id = " . $usuario_id . "";

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
        $sql = "SELECT *, (select count(1) from oq_grupo_usuario where grupo_id = g.id) As qtdUser FROM oq_grupo g where id = " . $id;
		$obj = new Grupo();
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->codigo = $g["codigo"];
				$obj->tags = $g["tags"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->dataCadastro = $g["dataCadastro"];
				$obj->ativo = $g["ativo"];
				$obj->deletado = $g["deletado"];
				$obj->qtdUser = $g["qtdUser"];
			}
		}
		mysqli_close($conn); 
		return $obj;
    } 
	
    public static function getCodigo($codigo,$empresaId) {
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_grupo where codigo = '" . $codigo . "' and empresa_id = ".$empresaId;
		$obj = new Grupo();
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->descricao = $g["descricao"];
				$obj->codigo = $g["codigo"];
				$obj->tags = $g["tags"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->dataCadastro = $g["dataCadastro"];
				$obj->ativo = $g["ativo"];
				$obj->deletado = $g["deletado"];
			}
		}
		mysqli_close($conn); 
		return $obj;
    } 
	
    public static function getNome($codigo,$usuarioId,$empresaId) {
		$conn = MySql::conectar();
        $sql = "SELECT id FROM oq_grupo where nome = '" . $codigo . "' and usuario_id = " . $usuarioId . " and empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["id"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 

    public static function getQtdeTotal() {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_grupo where deletado = 0";
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
        $sql = "select count(1) as qtd from oq_grupo where deletado = 0 and empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
    public static function getUsuario($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT DISTINCT g.*, (select count(1) from oq_video where deletado = 0 and grupo_id = g.id) As qtdVideo FROM oq_grupo g " .
		"left join oq_grupo_usuario gu on g.id = gu.grupo_id " .
		"where g.usuario_id = " . $id . " or gu.usuario_id = " . $id;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Grupo();
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->codigo = $g["codigo"];
				$obj->tags = $g["tags"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->dataCadastro = $g["dataCadastro"];
				$obj->ativo = $g["ativo"];
				$obj->deletado = $g["deletado"];
				$obj->qtdVideo = $g["qtdVideo"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
    public static function getUsuarioPage($id,$page) { 
		$qtd = 20;
		$offset = ($page -1) * $qtd; 
		$conn = MySql::conectar();
        $sql = "SELECT DISTINCT g.*, (select count(1) from oq_video where deletado = 0 and grupo_id = g.id) As qtdVideo FROM oq_grupo g " .
		"left join oq_grupo_usuario gu on g.id = gu.grupo_id " .
		"where g.usuario_id = " . $id . " or gu.usuario_id = " . $id . " order by g.id desc LIMIT " . $qtd . " OFFSET " . $offset;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($g = mysqli_fetch_assoc($result)){
				$obj = new Grupo();
				$obj->id = $g["id"]; 
				$obj->nome = $g["nome"];
				$obj->descricao = $g["descricao"];
				$obj->codigo = $g["codigo"];
				$obj->tags = $g["tags"];
				$obj->usuario_id = $g["usuario_id"];
				$obj->dataCadastro = $g["dataCadastro"];
				$obj->ativo = $g["ativo"];
				$obj->deletado = $g["deletado"];
				$obj->qtdVideo = $g["qtdVideo"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
} 
?>