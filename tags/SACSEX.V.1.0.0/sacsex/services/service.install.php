<?php
	/**
	 * install.php
	 * 
	 * Este servicio es utilizado por el script 'install.sh' 
	 * Servicio install.php que recibe como parametros usuario y pass en md5
	 * Modifica el campo INSTALAT de 0 a 1 del usuario en la tabla user
	 * conforme se ha instalado la aplicacion.
	 * Retorna 0 si la modificación ha sido correcta
	 * 		   1 si no se ha podido modificar
	 */

	include_once "../includes/servicesHeaders.php";

	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	$id=verificaUser($user, $pass, $link);
	if ( $id != ''){
		// Se modifica el valor a 1 del campo INSTALAT de la base de datos
		$query="UPDATE user SET INSTALAT=1 WHERE ID=$id";
		$result=mysql_query($query,$link);
		//Devolvera 0 si se ha podido proceder. En caso contrario devuelve 1
		if ($result){
			echo 0; 
		}else{
			echo 1;
		}
	}else{
		echo 1;
	}
	desconectar($link);
?>