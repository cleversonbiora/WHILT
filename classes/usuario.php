<?php 
include_once "conexao.php";
class Usuario { 
    public $id; 
    public $nome;
    public $email;
    public $senha;
    public $nascimento;
    public $avatar;
    public $facebook;
	public $dataCadastro;
	public $empresa_id;
	public $tipo;
	public $deletado;
	public $tempSenha;
	
    public static function cadastrar($email,$senha,$empresaId,$tipo,$facebook="",$nome="",$nascimento="") { 
		$conn = MySql::conectar();
        $sql = "INSERT INTO oq_usuario (email, senha, facebook, nome, nascimento,dataCadastro,deletado,empresa_id,tipo)
			VALUES ('" . $email . "', '" . md5($senha) . "', '" . $facebook . "','" . $nome . "', '" . $nascimento . "', NOW(),0,'.$empresaId.'," . $tipo . ")";

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
	
	public static function recuperarSenha($id) { 
		$conn = MySql::conectar();
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$codigo = '';
		foreach (array_rand($seed, 6) as $k) $codigo .= $seed[$k];
		$conn = MySql::conectar();
        $sql = "UPDATE oq_usuario SET tempSenhs = '" . md5($codigo) . "' where id = " . $id;
		//send mail
		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return $codigo;
		} else {
			mysqli_close($conn);
			return "";
		}
    } 
	
	public static function alterarAvatar($id, $nome) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_usuario SET avatar = '" . $nome . "' where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
	
	public static function alterarSenha($id, $senha) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_usuario SET senha = '" . md5($senha) . "' where id = " . $id;

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return 1;
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 

	public static function login($email,$senha,$empresaId) { 
		$conn = MySql::conectar();
        //$sql = "SELECT * FROM oq_usuario where deletado = 0 and email = '" . $email . "' and (senha = '" . md5($senha) . "' or tempSenhs = '" . md5($senha) . "') and empresa_id = ".$empresaId;
		$sql = "SELECT * FROM oq_usuario where deletado = 0 and email = '" . $email . "' and senha = '" . md5($senha) . "' and empresa_id = ".$empresaId;
		
		if ($result = mysqli_query($conn, $sql)) {
			if($evento = mysqli_fetch_assoc($result)){
				mysqli_close($conn);
				return $evento["id"];
			}else{
				$sql = "SELECT * FROM oq_usuario where deletado = 0 and email = '" . $email . "' and tempSenhs = '" . md5($senha) . "' and empresa_id = ".$empresaId;
				if ($result = mysqli_query($conn, $sql)) {
					if($evento = mysqli_fetch_assoc($result)){
						mysqli_close($conn);
						$_SESSION["recover"] = 1;
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
		} else {
			mysqli_close($conn);
			return 0;
		}
    } 
	
    public static function loginMasterAdmin($email,$senha,$empresaId) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario where deletado = 0 and tipo = 4  and email = '" . $email . "' and senha = '" . md5($senha) . "' and empresa_id = ".$empresaId;
		
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
    }   public static function loginAdmin($email,$senha,$empresaId) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario where deletado = 0 and tipo = 1 and email = '" . $email . "' and senha = '" . md5($senha) . "' and empresa_id = ".$empresaId;
		
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
	
    public static function getEmail($email,$empresaId) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario where email = '" . $email . "' and empresa_id = ".$empresaId;
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
        $sql = "SELECT * FROM oq_usuario where id = " . $id;
		$obj = new Usuario();
		if ($result = mysqli_query($conn, $sql)) {
			if($u = mysqli_fetch_assoc($result)){
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->email = $u["email"];
				$obj->senha = $u["senha"];
				$obj->nascimento = $u["nascimento"];
				$obj->avatar = $u["avatar"];
				$obj->facebook = $u["facebook"];
				$obj->dataCadastro = $u["dataCadastro"];
				$obj->tipo = $u["tipo"];
				$obj->deletado = $u["deletado"];
			}
		}
		mysqli_close($conn);
		return $obj;
    } 
	
	public static function getGrupo($id) { 
		$grupo = Grupo::getId($id);
		$adm = Usuario::getId($grupo->usuario_id);
		$conn = MySql::conectar();
        $sql = "SELECT g.* FROM oq_usuario g " .
		"inner join oq_grupo_usuario gu on g.id = gu.usuario_id " .
		"where gu.grupo_id = " . $id;
		$lista = array();
		$adm->nome = $adm->nome . " (Adm)";
		array_push($lista, $adm);
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new Usuario();
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->tipo = $u["tipo"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
	
    public static function getQtdeAlunosTotal() {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0 and tipo = 3";
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
    public static function getQtdeTotal() {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0";
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
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0 and empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
	
    public static function getQtdeProfTotal() {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0 and tipo = 2";
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
    public static function getQtdeAlunos($empresaId) {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0 and tipo = 3 and empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
	
    public static function getQtdeProf($empresaId) {
		$conn = MySql::conectar();
        $sql = "select count(1) as qtd from oq_usuario where deletado = 0 and tipo = 2 and empresa_id = ".$empresaId;
		$id = 0;
		if ($result = mysqli_query($conn, $sql)) {
			if($g = mysqli_fetch_assoc($result)){
				$id = $g["qtd"]; 
			}
		}
		mysqli_close($conn); 
		return $id;
    } 
	
		public static function getTipo($tipo,$empresaId) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario " .
		"where tipo = " . $tipo." and empresa_id = ".$empresaId;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new Usuario();
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->email = $u["email"];
				$obj->tipo = $u["tipo"];
				$obj->deletado = $u["deletado"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
		public static function getTodosTipo($tipo) { 
		$conn = MySql::conectar();
        $sql = "SELECT * FROM oq_usuario " .
		"where tipo = " . $tipo;
		$lista = array();
		if ($result = mysqli_query($conn, $sql)) {
			while($u = mysqli_fetch_assoc($result)){
				$obj = new Usuario();
				$obj->id = $u["id"]; 
				$obj->nome = $u["nome"];
				$obj->email = $u["email"];
				$obj->tipo = $u["tipo"];
				$obj->deletado = $u["deletado"];
				array_push($lista, $obj);
			}
		}
		mysqli_close($conn);
		return $lista;
    } 
	
	public static function deletar($id) { 
		$conn = MySql::conectar();
        $sql = "UPDATE oq_usuario SET deletado = 1 where id = " . $id;

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
        $sql = "UPDATE oq_usuario SET deletado = 0 where id = " . $id;

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