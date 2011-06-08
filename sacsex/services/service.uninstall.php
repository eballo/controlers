<?php
	/**
	 * uninstall.php
	 * 
	 * Este servicio es utilizado por los scripts 'install.sh' y 'uninstall.sh' 
	 * Servicio uninstall.php que recibe como parametros usuario y pass en md5
	 * Modifica el campo INSTALAT de 1 a 0 del usuario en la tabla user
	 * conforme se ha desinstalado la aplicacion.
	 * Retorna 0 si la modificación ha sido correcta
	 * 		   1 si no se ha podido modificar
	 */

	include_once "../includes/servicesHeaders.php";

	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	$id=verificaUser($user, $pass, $link);
	
	if ( $id != ''){
		$query="UPDATE user SET INSTALAT=0 WHERE ID=$id";
		$result=mysql_query($query,$link);
		//Devolvera 0 si se ha podido proceder. En caso contrario devuelve 1
		if ($result){
			echo "0:Ok"; 
		}else{
			echo "1:Fallo al dar de baja la aplicacion en la BD";
		}
	}else{
		echo "1:El usuario no ha sido localizado";
	}
	desconectar($link);
	
?>