<?php
/**
 * Clase principal que contiene todos los metodos para trabajar con la bbdd
 */
class gestionLibros{
	/**
	 * Este metodo conecta con la base de datos
	 * @return objeto|null
	 */
	public function conexion() {
		$mysqli = @new mysqli("localhost", "tarea6", "tarea6", "Libros");
		if (!$mysqli->connect_error)
		{
			return $mysqli;
		}
		else
		{
			echo "Error de conexión a la base de datos: " . $mysqli->connect_error;
			return null;
		}
	}
	/**
	 * Este metodo devuelve un array con la lista de autores
	 * @param $mysqli es la conexion a la bbdd
	 * @return array|null
	 */
	function get_lista_autores($mysqli){
		$sql="SELECT id,nombre,apellidos FROM Autor";
		if ($resultado = $mysqli->query($sql))
		{
			if ($mysqli->error)
			{
				echo "Error al consultar: " . $mysqli->error;
				return null;
			}
			else
			{
				while ($fila = $resultado->fetch_assoc()) {	
					$final[] = $fila;
				}
				$resultado->free();
				return $final;
			}
		}
	}
	/**
	 * Este metodo devuelve un array con los libros que coincidan con el id
	 * Si no especifica id, devuelve todos, si hay error null
	 * @param $id es el id pasado del autor
	 * @param $mysqli es la conexion a la bbdd
	 * @return array|null
	 */
	function get_lista_libros($mysqli, $id=-1) {
		$sql="SELECT id,titulo FROM Libro";
		if ($id!=-1){
			$sql="SELECT id,titulo FROM Libro WHERE id_autor=$id";
		}
		if ($resultado = $mysqli->query($sql))
		{
			if ($mysqli->error)
			{
				echo "Error al consultar: " . $mysqli->error;
				return null;
			}
			else
			{
				while ($fila = $resultado->fetch_assoc()) {	
					$final[] = $fila;
				}
				$resultado->free();
				return $final;
			}
		}
	}
	/**
	 * Este metodo devuelve un array con datos del autor que coincida con el id
	 * @param $id es el id pasado del autor
	 * @param $mysqli es la conexion a la bbdd
	 * @return array|null
	 */
	function get_datos_autor($mysqli,$id){
		$sql="SELECT nombre,apellidos,nacionalidad FROM Autor WHERE id=$id";
		if ($resultado = $mysqli->query($sql)){
			if ($mysqli->error){
				echo "Error al consultar: " . $mysqli->error;
				return null;
			} else {
				return $resultado->fetch_assoc();
			}
		}
	}
	/**
	 * Este metodo devuelve un array con los datos del libro que coincidan con el id
	 * @param $id es el id pasado del libro
	 * @param $mysqli es la conexion a la bbdd
	 * @return array|null
	 */
	function get_datos_libro($mysqli,$id){
		$sql="select l.titulo, l.f_publicacion, a.id, a.nombre, a.apellidos from Libro l join Autor a on(l.id_autor=a.id) where l.id=$id";
		if ($resultado = $mysqli->query($sql)){
			if ($mysqli->error){
				echo "Error al consultar: " . $mysqli->error;
				return null;
			} else {
				return $resultado->fetch_assoc();
			}
		}
	}
}
//aqui ya empezamos instanciando la clase y haciendo uso de ella
$bbdd = new gestionLibros();
$mysqli=$bbdd->conexion();
$posibles_URL = array("get_lista_autores", "get_datos_autor", "get_lista_libros", "get_datos_libro");
$valor = "Ha ocurrido un error";
//dependiendo de que nos pida el cliente llamaremos a un metodo u otro
if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL))
{
  switch ($_GET["action"])
    {
      case "get_lista_autores":
        $valor = $bbdd->get_lista_autores($mysqli);
        break;
	  case "get_lista_libros":
        if (isset($_GET["id"])){
            $valor = $bbdd->get_lista_libros($mysqli, $_GET["id"]);
        }else
            $valor = $bbdd->get_lista_libros($mysqli);
        break;
      case "get_datos_autor":
        if (isset($_GET["id"])){
            $valor = $bbdd->get_datos_autor($mysqli, $_GET["id"]);
        }else
            $valor = "Argumento no encontrado";
        break;
	  case "get_datos_libro":
        if (isset($_GET["id"])){
            $valor = $bbdd->get_datos_libro($mysqli, $_GET["id"]);
        }else
            $valor = "Argumento no encontrado";
        break;
    }
}
//por ultimo devolvemos los datos serializados en JSON
exit(json_encode($valor));
?>