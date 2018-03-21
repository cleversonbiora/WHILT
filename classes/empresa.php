<?php 
include_once "conexao.php";
class Empresa { 
    public $id; 
    public $nome;
    public $email;
    public $senha;
    public $telefone;
    public $logo;
    public $cor;
    public $url;
	public $ativo;
	
    /*public static function cadastrar($email,$senha,$facebook="",$nome="",$nascimento="") { 
		$conn = MySql::conectar();
        $sql = "INSERT INTO oq_usuario (email, senha, facebook, nome, nascimento,dataCadastro,deletado)
			VALUES ('" . $email . "', '" . md5($senha) . "', '" . $facebook . "','" . $nome . "', '" . $nascimento . "', NOW(),0)";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
	public static function alterarNome($id, $nome) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_usuario SET nome = '" . $nome . "' where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    public static function login($email,$senha) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario where email = '" . $email . "' and senha = '" . md5($senha) . "'";
		
		if ($result = mysqli_query($conn, $sql)) {
			if($evento = mysqli_fetch_assoc($result)){
				mysqli_close($conn);
				return $evento["id"];
			}else{
				mysqli_close($conn);
				return 0;
			}
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	*/
	public static function alterar($id, $cor, $logo) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_empresa SET cor = '" . $cor . "',  logo = '" . $logo . "' where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    public static function getUrl($url) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_empresa where url = '" . $url . "'";
		if ($result = mysqli_query($conn, $sql)) {
			if($evento = mysqli_fetch_assoc($result)){
				mysqli_close($conn);
				return $evento["id"];
			}else{
				mysqli_close($conn);
				return 0;
			}
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    public static function getId($id) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_empresa where id = " . $id;
		$obj = new Empresa();
		if ($result = mysqli_query($conn, $sql)) {
			if($u = mysqli_fetch_assoc($result)){
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->email = $u["email"];
				$obj->senha = $u["senha"];
				$obj->telefone = $u["telefone"];
				$obj->logo = $u["logo"];
				$obj->cor = $u["cor"];
				$obj->url = $u["url"];
				$obj->ativo = $u["ativo"];
			}
		}
		mysqli_close($conn);
		return $obj;
    } 
	
	public static function get() { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_empresa";
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new Empresa();
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->email = $u["email"];
				$obj->senha = $u["senha"];
				$obj->telefone = $u["telefone"];
				$obj->logo = $u["logo"];
				$obj->cor = $u["cor"];
				$obj->url = $u["url"];
				$obj->ativo = $u["ativo"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	public static function deletar($id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_empresa SET ativo = 0 where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
	public static function ativar($id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_empresa SET ativo = 1 where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    }
} 
?>