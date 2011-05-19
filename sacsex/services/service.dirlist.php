<?php
 	include_once '../includes/servicesHeaders.php';
 
 	//Recibe el id de usuario y devuelve todas las rutas almacenadas en la BD separadas por un espacio
 	$ID=$_GET['id'];
 	$link=conectar('bdsintesi');
 	
 	$query="SELECT FILEPATH FROM filepath where USER_ID='$ID'";
 	$result=mysql_query($query,$link);
 	$rows=mysql_num_rows($result);
 	$fich="";
 	if ($rows > 0){
 		while ($row=mysql_fetch_array($result)){
 			$fich=$fich." ".$row['FILEPATH'];
 		}
 		desconectar($link);	
 	}
 	echo $fich;
?>