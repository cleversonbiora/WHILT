<?php
	if(isset($_POST["cmd"]) && strlen($_POST["cmd"]) > 0){
		if($_POST["cmd"] == "insertGrupo"){
			Grupo::cadastrar($_POST["nome"],"",$_SESSION["user"],"");
		}
		/*if($_POST["cmd"] == "delete"){
			$sql = "DELETE FROM Evento where id = " . $_POST["id"];

			if (mysqli_query($conn, $sql)) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}else if($_POST["cmd"] == "update"){
			$sql = "UPDATE Evento set codigo = '" . $_POST["codigo"] . "' where id = " . $_POST["id"];

			if (mysqli_query($conn, $sql)) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}else if($_POST["cmd"] == "deleteEmp"){
			$sql = "UPDATE Empresa set deletado = 1 where id = " . $_POST["id"];

			if (mysqli_query($conn, $sql)) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}else if($_POST["cmd"] == "updateEmp"){
			$sql = "UPDATE Empresa set url = '" . $_POST["url"] . "' where id = " . $_POST["id"];

			if (mysqli_query($conn, $sql)) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}else{
			$sql = "INSERT INTO Evento (nome, url, codigo, empresa_id)
			VALUES ('" . $_POST["nome"] . "', '" . $_POST["url"] . "', '" . $_POST["codigo"] . "', " . $empresa_id  . ")";

			if (mysqli_query($conn, $sql)) {
				//echo "New record created successfully";
			} else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}*/
		
	}
?>