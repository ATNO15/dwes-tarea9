<?php
	$salida = "";
	if (isset($_GET["q"])){
		$titulo = $_GET["q"];
		$mysqli = new mysqli("localhost", "tarea6", "tarea6", "Libros");
		if (!$mysqli->connect_error){
			$mysqli->set_charset("utf8");
			$sql = "SELECT * FROM libro WHERE titulo LIKE '%$titulo%' ORDER BY titulo";
			if (($resultado = $mysqli->query($sql)) && (!$mysqli->error)){
				while ($fila = $resultado->fetch_assoc()){
					$salida = $salida . "<br>". $fila["titulo"];
				}
				$resultado->free();
				$mysqli->close();
			}
		}
	}
	echo $salida;
?>