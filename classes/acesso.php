<?php 
include_once "conexao.php";
class Acesso { 
    public $codigo; 
    public $usuario_id;
    public $data;
	
    public static function cadastrar($id) { 
		$conn = MySql::conectar();
		$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
		shuffle($seed); // probably optional since array_is randomized; this may be redundant
		$codigo = '';
		foreach (array_rand($seed, 50) as $k) $codigo .= $seed[$k];
        $sql = "INSERT INTO oq_acesso (codigo, usuario_id, data)
			VALUES ('" . $codigo . "'," . $id . ", NOW())";

		if (mysqli_query($conn, $sql)) {
			mysqli_close($conn);
			return $codigo;
		} else {
			mysqli_close($conn);
			return "";
		}
    } 
	
    public static function get($codigo) { 
		$conn = MySql::conectar();
        $sql = "SELECT usuario_id FROM oq_acesso where codigo = '" . $codigo . "'";
		$ret = "";
		if ($result = mysqli_query($conn, $sql)) {
			if($u = mysqli_fetch_assoc($result)){
				$ret = $u["usuario_id"]; 
			}
		}
		mysqli_close($conn);
		return $ret;
    } 
} 
?>