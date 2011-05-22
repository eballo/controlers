<?php
	include_once "../includes/servicesHeaders.php";

//             install.php recibe como parametros el usuario y la contraseña en md5 y
//					le asigna un 1 a la celda "INSTALAT" conforme se ha instalado la aplicacion
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar('bdsintesi');
	$id=verificaUser($user, $pass, $link);
	if ( $id != ''){
		$query="UPDATE user SET INSTALAT=1 WHERE ID=$id";
		$result=mysql_query($query,$link);
		//Devolverá 0 si se ha podido proceder. En caso contrario de vuelve 1
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