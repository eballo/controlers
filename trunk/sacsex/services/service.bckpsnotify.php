<?php
	include_once '../includes/servicesHeaders.php';
//TODO Servicio de notificacion de backups:
//		bckpsnotify.php: Recibe como parametros de entrada  usuario, passwd, fichero, fecha i tama�o
//			Comprueba, por un lado que el fichero no exceda el tama�o permitido diario ni el total de 
//			espacio reservado al usuario.
//			Si todo es correcto, sube los datos a la tabla backups i devuelve un 0
//			Si no, devolver� un 1 si el usuario ha excedido el espacio
//				y un 2 CUANDO?
//			En cualquier caso (devuelva un 1 o un 2) se eliminara el BK del servidor
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$file=$_GET['file'];
	$date=$_GET['date'];
	$size=$_GET['size'];
	
	$link=conectar('bdsintesi');
	//busco el ID del usuario
	$id=verificaUser($user, $pass, $link);
	if ( $id!=''){
		$oldname=$GLOBALS['TMP_PATH']."/".$file;
		$newpath=$GLOBALS['BKPS_PATH']."/".$id."/".$file;
		if(rename($oldname,$newpath)){
			$insQ="INSERT into backups (FILENAME,SIZE,DATE,USER_ID) VALUES ('$file',$size,'$date',$id)";
			$res=mysql_query($insQ,$link);
			if ($res){
				echo "0:";
			}else{
				echo "Error: No se ha podido hacer el insert";
			}
		}else{
			echo "Error: No se ha podido mover a backups";
		}
	}else{
		echo "Error: No se ha localizado ningun usuario con los datos aportados";
	}
	desconectar($link);
?>