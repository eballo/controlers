<?php
	include_once '../includes/servicesHeaders.php';
	//Comprobara si existe el usuario en la BD y si, en la casilla de "INSTALL" de la BD
	// Tiene un 0 o un 1

	//Conexion base de datos
	$link=conectar('bdsintesi');
	
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$install=$_GET['install'];
	$id=verificaUser($user, $pass,$link);
	$resp=3;
	
	if ($install=="true"){
		if ($id!=""){
			$query="SELECT INSTALAT from user where ID=$id";
			$busca=mysql_query($query,$link);
			$isinstallA=mysql_fetch_array($busca);
			$isinstall=($isinstallA[0])+2;
			$resp="$isinstall/$id";
		}
		echo $resp;
	}else{
		echo $id;
	}
	
	desconectar($link);
?>