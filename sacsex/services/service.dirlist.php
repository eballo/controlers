<?php
	/**
	 * dirlist.php
	 * 
	 * Este servicio es utilizado por el script 'backups-sacs2.sh'
	 * Servicio dirlist.php que recibe como parametro usuario y password en md5
	 * Recoge todas las rutas de la base de datos y las valida
	 * Retorna las rutas validas separadas por un espacio
	 */
	
 	include_once '../includes/servicesHeaders.php';

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