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

	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$file=$_GET['file'];
	$date=$_GET['date'];
	$size=$_GET['size'];
	$bd=$GLOBALS['MYSQL_BDNAME'];
	$link=conectar($bd);
	//busco el ID del usuario
	$id=verificaUser($user, $pass, $link);
	if ( $id!=''){
		$purgarQ="SELECT VALOR,FREQ from purga WHERE USER_ID=$id";
		$res=mysql_query($purgarQ,$link);
		$purgarA=mysql_fetch_array($res);
		$valor=$purgarA[0];
		$freq=$purgarA[1];
		if ($valor!=0){
			//
			$dias=$valor;
			switch ($freq) {
				case 0:
					$text='DAY';
					break;
				case 1:
					$text='MONTH';
					break;
				case 2:
					$text='YEAR';
				break;
			} 
			$dateQ=" AND TIMESTAMPDIFF($text,TIMEDATE,curdate()) >=". $dias;
			$query="SELECT * FROM backups WHERE USER_ID=$id".$dateQ.";";
echo $query;
			$res=mysql_query($query,$link);
			$path=$GLOBALS['BKPS_PATH']."/".$id;
			echo $path;
			while ($row=mysql_fetch_array($res)){
				echo $path."/".$row['FILENAME'];

				if(unlink($path."/".$row['FILENAME'])){
					$delQ="DELETE from backups WHERE USER_ID=".$id." AND ID=".$row['ID'];
					$res=mysql_query($delQ,$link);			
				}
			}
		}						
		$oldname=$GLOBALS['TMP_PATH']."/".$file;
		$filename=explode("_",$file);
		$filename=$filename[1]; //modifico el nombre del fichero para quitarle el ID de usuario
		$newpath=$GLOBALS['BKPS_PATH']."/".$id."/".$filename;
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
