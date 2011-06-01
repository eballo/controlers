<?php
	include_once '../includes/servicesHeaders.php';
	
	//Recibe id de Fichero, nombre y password(md5) de usuario y lo elimina de la BD
	$idf=$_GET['idf'];
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	
	$link=conectar('bdsintesi');
	$id=verificaUser($user, $pass, $link);
	if ( $id != ''){
		$delQuery = "DELETE FROM backups WHERE ID='$idf' AND USER_ID=$id";
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
	desconectar($link);
?>