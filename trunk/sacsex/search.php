<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	include_once 'includes/cabecera.php';
	
	$link=conectar('bdsintesi');
	
	$id=$_SESSION['id'];
	if (isset($_GET['accion'])){
		$accion=$_GET['accion'];
		if ($accion=="subir"){
			$filepath=$_POST['filepath'];
			$insQuery="INSERT INTO filepath (FILEPATH,USER_ID) VALUES ('$filepath',$id)";
			$res=mysql_query($insQuery,$link);
			if (!$res){
				$error="Error: No se ha podido realizar la insercion";
			}
		}elseif($accion=="borrar"){
			$idFile=$_GET['idFile'];	
			$delQuery="DELETE FROM filepath WHERE IDF=$idFile";
			$rest=mysql_query($delQuery,$link);
			if (!$rest){
				$error="No se pudo eliminar el directorio indicado";
			}
		}
	}	
	$query="SELECT USER_ID,FILEPATH,IDF FROM filepath WHERE USER_ID=$id";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	echo "<h2> Modificacion de rutas de Directorio </h2>";
	if ($rows >0){
		echo "<h3> Directorios: </h3>";
		echo "<table><tr><th colspan='2'>Rutas Del Usuario:</th></tr>";
		while($row=mysql_fetch_array($result)){
			printf("<tr><td>%s</td><td><a href=\"search.php?accion=borrar&idFile=%d\">Eliminar</a></td></tr>",$row['FILEPATH'],$row['IDF']);
		}
		echo "</table><br>";
	}
	desconectar($link);

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Alta de Files de Usuario</title>
</head>
<body>

<table><tr><td rowspan='2'></table>
	<h3> Alta de File: </h3>
	<form action='search.php?accion=subir' method='post'>
		File: <input type="text" name='filepath' />
		<input type="submit" value='valida' /><br />
	</form>
	<a href='asistenteBusqueda.php'>Buscar</a>
	<?php 
	if ($error!=''){
			echo "<script type='text/javascript'>
				alert('$error');
			</script>";
		}
	?>
</body>
</html>