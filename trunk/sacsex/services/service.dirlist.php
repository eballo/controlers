<?php
 	include_once '../includes/servicesHeaders.php';
 
 	//Recibe nombre y password(md5) de usuario y 
 	// devuelve todas las rutas validas almacenadas en la BD separadas por un espacio

 	$user=$_GET['user'];
	$pass=$_GET['pass'];
 	$fich="";
 	
 	$link=conectar('bdsintesi');
 	$id=verificaUser($user, $pass, $link);
	if ( $id != ''){
	 	$query="SELECT FILEPATH FROM filepath where USER_ID='$id' AND VALID=0";
	 	$result=mysql_query($query,$link);
	 	$rows=mysql_num_rows($result);
	 	if ($rows > 0){
	 		while ($row=mysql_fetch_array($result)){
	 			$fich=$fich." ".$row['FILEPATH'];
	 		}
	 	}
	}
	echo $fich;
 	desconectar($link);	
?>