<!DOCTYPE html>
<html lang="es">
<html>
 <head>
	<meta charset=”utf-8”>
	<title>tarea9</title>
	<script>
		/**
		 * Este metodo se encarga de recoger el valor introducido
		 * Si es un numero lo borra
		 * Si no se lo manda a sugerencias para que lo compare con la bbdd
		 */
		function mostrarSugerencia(){
			var texto = document.getElementById('texto');
			if (texto.value.length == 0){
				document.getElementById("sugerencias").innerHTML="";
			}else{
				var patron = new RegExp(/^[A-Za-z\s\,]+$/g);
				if(!patron.test(texto.value.charAt(texto.value.length - 1))){
					texto.value = texto.value.substring(0, texto.value.length - 1);
				}else{
					var asyncRequest = new XMLHttpRequest();
					asyncRequest.onreadystatechange=stateChange;
					asyncRequest.open("GET","sugerencias.php?q="+texto.value,true);
					asyncRequest.send(null);
					function stateChange(){
						if(asyncRequest.readyState==4 && asyncRequest.status==200){
							document.getElementById("sugerencias").innerHTML=asyncRequest.responseText;
						}
					}
				}
			}
		}
		/**
		 * Este metodo se encarga de recoger el valor introducido
		 * Si es un numero lo borra
		 * Si no se lo manda a sugerencias2 para que lo compare con la bbdd
		 */
		function mostrarSugerencia2(){
			var texto = document.getElementById('texto2');
			if (texto.value.length == 0){
				document.getElementById("sugerencias2").innerHTML="";
			}else{
				var patron = new RegExp(/^[A-Za-z\s\,]+$/g);
				if(!patron.test(texto.value.charAt(texto.value.length - 1))){
					texto.value = texto.value.substring(0, texto.value.length - 1);
				}else{
					var asyncRequest = new XMLHttpRequest();
					asyncRequest.onreadystatechange=stateChange;
					asyncRequest.open("GET","sugerencias2.php?q="+texto.value,true);
					asyncRequest.send(null);
					function stateChange(){
						if(asyncRequest.readyState==4 && asyncRequest.status==200){
							document.getElementById("sugerencias2").innerHTML=asyncRequest.responseText;
						}
					}
				}
			}
		}
	</script>
 </head>
 <body>
<?php
// Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a traves de su "id"...
if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_autor") 
{
    //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
    $app_info = file_get_contents('http://localhost/tarea9/api.php?action=get_datos_autor&id=' . $_GET["id"]);
	// Se decodifica el fichero JSON y se convierte a array
	$app_info = json_decode($app_info, true);
	
	// Pedimos al la api que nos devuelva una lista de libros. La respuesta se da en formato JSON
    $lista_libros = file_get_contents('http://localhost/tarea9/api.php?action=get_lista_libros&id=' . $_GET["id"]);
    // Convertimos el fichero JSON en array
	$lista_libros = json_decode($lista_libros, true);
?>
    <h3>Datos del autor:</h3>
	<table>
        <tr>
            <td>Nombre: </td><td> <?php echo $app_info["nombre"] ?></td>
        </tr>
        <tr>
            <td>Apellidos: </td><td> <?php echo $app_info["apellidos"] ?></td>
        </tr>
        <tr>
            <td>Fecha de nacimiento: </td><td> <?php echo $app_info["nacionalidad"] ?></td>
        </tr>
    </table>
	<h3>Libros escritos:</h3>
		<ul>
		<!-- Mostramos una entrada por cada libro -->
		<?php foreach($lista_libros as $libros): ?>
			<li>
				<!-- Enlazamos cada libro con su informacion -->
				<a href="<?php echo "http://localhost/tarea9/cliente.php?action=get_datos_libro&id=" . $libros["id"]  ?>">
				<?php echo $libros["id"] . " " . $libros["titulo"] ?>
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
    <br />
    <!-- Enlace para volver a inicio -->
    <a href="http://localhost/tarea9/cliente.php?action=get_lista_autores" alt="Lista de autores">Volver a inicio</a>
<?php
}else if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_libro") 
{
    //Se realiza la peticion a la api que nos devuelve el JSON con la información de los libros
    $app_info = file_get_contents('http://localhost/tarea9/api.php?action=get_datos_libro&id=' . $_GET["id"]);
	// Se decodifica el fichero JSON y se convierte a array
    $app_info = json_decode($app_info, true);
?>
    <h3>Datos del libro:</h3>
	<table>
        <tr>
            <td>Titulo: </td><td> <?php echo $app_info["titulo"] ?></td>
        </tr>
        <tr>
            <td>Fecha de publicacion: </td><td> <?php echo $app_info["f_publicacion"] ?></td>
        </tr>
		<tr>
            <td>Nombre del autor: </td><td> <a href="<?php echo "http://localhost/tarea9/cliente.php?action=get_datos_autor&id=" . $app_info["id"]  ?>">
												<?php echo $app_info["nombre"]." ".$app_info["apellidos"] ?></a></td>
        </tr>
    </table>
    <br />
    <!-- Enlace para volver a la lista de autores -->
    <a href="http://localhost/tarea9/cliente.php?action=get_lista_autores" alt="Lista de autores">Volver a inicio</a>
<?php
}
else //sino muestra la lista de autores
{
    // Pedimos al la api que nos devuelva una lista de autores. La respuesta se da en formato JSON
    $lista_autores = file_get_contents('http://localhost/tarea9/api.php?action=get_lista_autores');
    // Convertimos el fichero JSON en array
    $lista_autores = json_decode($lista_autores, true);
	
	// Pedimos al la api que nos devuelva una lista de libros. La respuesta se da en formato JSON
    $lista_libros = file_get_contents('http://localhost/tarea9/api.php?action=get_lista_libros');
    // Convertimos el fichero JSON en array
    $lista_libros = json_decode($lista_libros, true);
	
?>
    <h3>Autores:</h3>
	<ul>
    <!-- Mostramos una entrada por cada autor -->
    <?php foreach($lista_autores as $autores): ?>
        <li>
            <!-- Enlazamos cada nombre de autor con su informacion (primer if) -->
            <a href="<?php echo "http://localhost/tarea9/cliente.php?action=get_datos_autor&id=" . $autores["id"]  ?>">
            <?php echo $autores["nombre"] . " " . $autores["apellidos"] ?>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
	<h3>Libros:</h3>
	<ul>
    <!-- Mostramos una entrada por cada libro -->
    <?php foreach($lista_libros as $libros): ?>
        <li>
            <!-- Enlazamos cada libro con su informacion -->
            <a href="<?php echo "http://localhost/tarea9/cliente.php?action=get_datos_libro&id=" . $libros["id"]  ?>">
            <?php echo $libros["id"] . " " . $libros["titulo"] ?>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
	<h3>Buscar libro:</h3>
	<input type="search" id="texto" onkeyup="mostrarSugerencia();">
	<p><strong>Sugerencias: </strong><span id="sugerencias" style="color:#05F;"></span></p>
	<h3>Botones:</h3>
	<input onclick="alert('Usuario creado.');" type="button" value="Crear Usuario">
	<input onclick="confirm('¿Borrar fichero?');" type="button" value="Borrar Fichero">
	<h3>Buscar autor->libros:</h3>
	<input type="search" id="texto2" onkeyup="mostrarSugerencia2();">
	<p><strong>Sugerencias: </strong><span id="sugerencias2" style="color:#05F;"></span></p>
  <?php
} ?>
 </body>
</html>