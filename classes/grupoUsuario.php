<?php 
include_once "conexao.php";
class GrupoUsuario { 
    public $id; 
    public $grupo_id;
    public $usuario_id;
	
    public static function cadastrar($usuario_id,$grupo_id) { 
		$conn = MySql::conectar();
        $sql = "INSERT INTO oq_grupo_usuario (usuario_id, grupo_id)
			VALUES (" . $usuario_id . "," . $grupo_id . ")";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
	public static function sair($grupo_id, $usuario_id) { 
		$conn = MySql::conectar();
        $sql = "DELETE FROM oq_grupo_usuario where grupo_id = " . $grupo_id . " and usuario_id = " . $usuario_id . "";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    /*public static function getId($id) { 
        $sql = "SELECT * FROM oq_grupo where id = " . $id;
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
			}
		}
		return $obj;
    } */
	
    public static function getUsuario($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_grupo_usuario where usuario_id = " . $id;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new GrupoUsuario();
				$obj->id = $g["id"]; 
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
    public static function getGrupo($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_grupo_usuario where grupo_id = " . $id;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new GrupoUsuario();
				$obj->id = $g["id"]; 
				$obj->grupo_id = $g["grupo_id"];
				$obj->usuario_id = $g["usuario_id"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
} 
?>