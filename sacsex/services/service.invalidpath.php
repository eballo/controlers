<?php
	include_once "../includes/servicesHeaders.php";

	// Recibe ruta de directorio, nombre y password de usuario
	// Modifica el campo VALID en tabla 'filepath'
	//		Por defecto VALID es 0
	// 		Si la ruta FILEPATH no existe VALID es 1
	
	$ruta=$_GET['dir'];
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	echo "$ruta, $user, $pass";
	$link=conectar('bdsintesi');
	$id=verificaUser($user,$pass,$link);
	echo "ID:".$id;
	if ( $id != ''){
		$invQuery = "UPDATE filepath SET VALID=1 WHERE FILEPATH='$ruta' AND USER_ID=$id";
		$res=mysql_query($invQuery,$link);
		echo "Res=$res";
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