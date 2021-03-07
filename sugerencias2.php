<?php
	$salida = "";
	if (isset($_GET["q"])){
		$parametro = $_GET["q"];
		$mysqli = new mysqli("localhost", "tarea6", "tarea6", "Libros");
		if (!$mysqli->connect_error){
			$mysqli->set_charset("utf8");
			$sql = "SELECT * FROM Autor WHERE nombre LIKE '%$parametro%' OR apellidos LIKE '%$parametro%'";
			if (($resultado = $mysqli->query($sql)) && (!$mysqli->error)){
				while ($fila = $resultado->fetch_assoc()){
					$salida = $salida."<br>".$fila["nombre"]." ".$fila["apellidos"];
					$tempAutor=$fila["id"];
					$sql="select l.titulo from Libro l join Autor a on(l.id_autor=a.id)
					where l.titulo LIKE '%$parametro%' AND l.id_autor=$tempAutor ORDER BY l.titulo";				
					if (($resultado2 = $mysqli->query($sql)) && (!$mysqli->error)){
						while ($fila2 = $resultado2->fetch_assoc()){
							$salida = $salida."<br>->".$fila2["titulo"];
						}
						$resultado2->free();
					}
				}
				$resultado->free();
				$mysqli->close();
			}
		}
	}
	echo $salida;
?>