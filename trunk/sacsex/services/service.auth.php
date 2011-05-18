<?php
	include_once 'includes/functions.php';
	//Comprobara si existe el usuario en la BD y si, en la casilla de "INSTALL" de la BD
	// Tiene un 0 o un 1
	$user=$_GET['user'];
	$pass=$_GET['passwd'];
	$install=$_GET['install'];
	$id=verificaUser($user, $pass);
	
	if ($install!=""){
		$link=conectar('bdsintesi');
		if ($id!=""){
			$query="SELECT INSTALL from user where ID=$id";
			$busca=mysql_query($query,$link);
			$isinstallA=mysql_fetch_array($busca);
			$isinstall=($isinstallA[0])+2;
			$resp="$isinstall/$id";
		}
		return $resp;
	}else{
		return $id;
	}
	
?>