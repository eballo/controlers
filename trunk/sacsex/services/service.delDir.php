<?php
	include_once "../includes/servicesHeaders.php";
	
	//Recibe ruta directorio, nombre y password(md5) de usuario y elimina la ruta de la BD
	$ruta=$_GET['dir'];
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	//$id=$_GET['id'];
	
	$link=conectar('bdsintesi');
	$id=verificaUser($user, $pass, $link);
	if ( $id != ''){
		$delQuery = "DELETE FROM filepath WHERE FILEPATH='$ruta' AND USER_ID=$id";
		$res=mysql_query($delQuery,$link);
		if ($res){
			echo "/0";
		}else{
			echo "/1";
		}
	}
	else{
		echo "/1";
	}
?>