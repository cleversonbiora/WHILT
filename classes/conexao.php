<?php
class MySql { 
	
	public static function conectar() { 
		$servername = "localhost";
		$username = "oqueapre_db";
		$password = "075114aB";
		$dbname = "oqueapre_whilt";
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);

		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
			return null;
		}
		return $conn;
    } 
} 
?>