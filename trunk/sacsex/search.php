<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	//include_once 'includes/cabecera.php';
	
	$link=conectar('bdsintesi');
	
	/*$postsubir=$_POST['accion'];
	echo "Si weeey",$postsubir,"<br>";*/
	
	if (isset($_GET['error'])){
		$error = $_GET['error'];
	} else{
		$error = "";
	}
	
	$id=$_SESSION['id'];
	if (isset($_GET['accion'])){
		$accion = $_GET['accion'];
		if ($accion == "subir"){
			$filepath = $_POST['filepath'];
			if ($filepath != ""){
				$insQuery="INSERT INTO filepath (FILEPATH,USER_ID) VALUES ('$filepath',$id)";
				$res=mysql_query($insQuery,$link);
				if (!$res){
					echo "<script type='text/javascript'>
						alert('Error No se ha podido realizar la insercion');
					</script>";
				}
			} else {
				echo "<script type='text/javascript'>
					document.location = 'search.php?error=1';
				</script>";
			}
		}
		elseif($accion == "borrar"){
			$idFile = $_GET['idFile'];	
			$delQuery = "DELETE FROM filepath WHERE IDF=$idFile";
			$res = mysql_query($delQuery,$link);
			if (!$res){
				echo "<script type='text/javascript'>
					alert('No se pudo eliminar el directorio indicado');
				</script>";
				/*$error="No se pudo eliminar el directorio indicado";*/
			}
		}
	}
?>
<!--
<HTML>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Opciones del Usuario</title>
</head>
<body>
	<h3> OPCIONES </h3>
				<script type='text/javascript'>
					document.location = 'search.php?accion=subir';
				</script>";

</body>
-->
<?php
/* Borrar */
	$query="SELECT USER_ID,FILEPATH,IDF FROM filepath WHERE USER_ID=$id";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	echo "<h2> Modificacion de rutas de Directorio (En construccion)</h2>";
	if ($rows >0){
		echo "<h3> Directorios: </h3>";
		echo "<table><tr><th colspan='2'>Rutas Del Usuario:</th></tr>";
		while($row=mysql_fetch_array($result)){
			printf("<tr><td>%s</td><td><a href=\"search.php?accion=borrar&idFile=%d\">Eliminar</a></td></tr>",$row['FILEPATH'],$row['IDF']);
		}
		echo "</table><br>";
	}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Alta de Files de Usuario</title>
</head>
<body>
	
	<h3> Alta de File: </h3>
	<form action='search.php?accion=subir' method='POST'>
		<!-- <input type='hidden' name='accion' value='subir'/> -->
		<div id='divErrors' style='color: red'>		
			<?php
			if ($error){
				switch($error){
					case 1:
						/* Error al validar un archivo nulo */
						echo "Directorio nulo no valido.";break;
						
					/*echo "<script type='text/javascript'>
						alert('$error');
					</script>";*/
				}
			}
			?>
		</div>
		File: <input type="text" name='filepath' />
		<input type="submit" value='valida' /><br />
	</form>
	<a href='asistenteBusqueda.php'>Buscar</a>

</body>
</html>
<?php desconectar($link); ?>