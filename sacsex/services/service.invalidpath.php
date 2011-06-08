<?php
	/**
	 * invalidpath.php
	 * 
	 * Este servicio es utilizado por el script binario 'sacsex'
	 * Servicio invalidpath.php que recibe como parametro ruta de directorio, usuario y password en md5
	 * Modifica el campo a invalido de la BD con un '1' si la ruta no es valida 
	 * Retorna '/0' si la notificación a la base de datos es correcta
	 * 		   '/1' si no se ha podido hacer la notificacion o usuario invalido
	 */

	include_once "../includes/servicesHeaders.php";
	
	$ruta=$_GET['dir'];
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	$id=verificaUser($user,$pass,$link);
	
	if ( $id != ''){
		// Notificación a BD con '1' de ruta invalida
		$invQuery = "UPDATE filepath SET VALID=1 WHERE FILEPATH='$ruta' AND USER_ID=$id";
		$res=mysql_query($invQuery,$link);

		if ($res){
			echo "/0";
		}else{
			echo "/1";
		}
	}
	else{
		echo "/1";
	}
	desconectar(link);

?>