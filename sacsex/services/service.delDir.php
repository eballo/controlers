<?php
	include_once '../includes/functions.php';
	
	//elimina una ruta de la BD
	$ruta=$_GET['dir'];
	$id=$_GET['id'];
	
	$link=conectar('bdsintesi');
	$delQuery = "DELETE FROM filepath WHERE FILEPATH='$ruta' AND USER_ID=$id";
	$res=mysql_query($delQuery,$link);
	if ($res){
		echo "/0";
	}else{
		echo "/1";
	}
?>