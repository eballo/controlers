<?php
	/**
	 * bckpsnotify.php
	 * 
	 * Este servicio es utilizado por el script 'backups-sacs2.sh'
	 * Servicio bckpsnotify.php que recibe como parametro usuario, password en md5, nombre del fichero tar
	 * fecha de creación del tar y tamaño del tar
	 * Comprueba, por un lado que el fichero tar no exceda el tamaño de subida ni el espacio total reservado.
	 * Mueve el fichero tar que esta en la carpeta temporal de backups hacia el directorio final.
	 * Retorna '0:' si la notificación del insert del fichero tar backup ha sido correcto 
	 * 			Caso contrario una descripción del error
	 */

	include_once '../includes/servicesHeaders.php';

	$user=limpiar($_GET['user']);
	$pass=limpiar($_GET['pass']);
	$file=limpiar($_GET['file']);
	$date=limpiar($_GET['date']);
	$size=limpiar($_GET['size']);
	$bd=$GLOBALS['MYSQL_BDNAME'];
	$link=conectar($bd);
	//busco el ID del usuario
	$id=verificaUser($user, $pass, $link);
	if ( $id!=''){
						
		$oldname=$GLOBALS['TMP_PATH']."/".$file;
		$filename=explode("_",$file);
		// Modifico el nombre del fichero para quitarle el ID de usuario
		$filename=$filename[1];
		$newpath=$GLOBALS['BKPS_PATH']."/".$id."/".$filename;
		
		// Movemos el backup del directorio temporal hacia directorio final
		if(rename($oldname,$newpath)){
			$insQ="INSERT into backups (FILENAME,SIZE,TIMEDATE,USER_ID) VALUES ('$filename',$size,'$date',$id)";
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
