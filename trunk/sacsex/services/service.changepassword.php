<?php
	/**
	 * changepassword.php
	 * 
	 * Este servicio es utilizado por el script 'reconfigure.sh'
	 * Servicio reconfigure.php que recibe como parametro usuario, password antiguo en md5 y password nuevo en md5
	 * Valida el usuario si es correcto y modifica el password de usuario en BD por el nuevo que se ha pasado 
	 * Retorna '0:' si la notificación de la modificación del password ha sido correcto 
	 * 		   '1:' descripción del error en caso contrario
	 */

	include_once '../includes/servicesHeaders.php';
	
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$pwd5mdnew=$_GET['passnew'];

	$link=conectar('bdsintesi');
	$id=verificaUser($user, $pass, $link);
	
	if ( $id != ''){
		$query="UPDATE user SET PASSWORD='$pwd5mdnew' WHERE ID=$id";
		$result=mysql_query($query,$link);
		//Devolvera 0 si se ha podido proceder. En caso contrario devuelve 1		
		if ($result){
			echo "0:Ok"; 
		}else{
			echo "1:Fallo al modificar el password a usuario en la BD";
		}
	}else{
		echo "1:El usuario no ha sido localizado";
	}
	desconectar($link);
	


