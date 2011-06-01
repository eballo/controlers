<?php
	include_once "../includes/servicesHeaders.php";

// 				uninstall.php recibe como parametros el usuario y la contrase�a en md5 y 
//					le asigna un 0 conforme la aplicacion fue desinstalada.
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar('bdsintesi');
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