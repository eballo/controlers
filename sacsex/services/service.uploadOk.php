<?php
	/**
	 * uploadOk.php
	 * 
	 * Este servicio es utilizado por el script binario 'sacsex'
	 * Servicio uploadOk.php que recibe como parametro usuario, password en md5, nombre del fichero tar y tamaño del tar
	 * Comprueba que el fichero tar backup no exceda el tamaño de subida ni que sobrepase el limite de espacio en servidor.
	 * Retorna los datos de conexión para scp si ha cumplido las condiciones
	 */

	include_once '../includes/servicesHeaders.php';

	$user=limpiar($_GET['user']);
	$pass=limpiar($_GET['pass']);
	$file=limpiar($_GET['file']);
	$size=limpiar($_GET['size']);
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	//busco el ID del usuario
	$id=verificaUser($user, $pass, $link);
	if ( $id!=''){	
		//Se realiza la purga de archivos segun lña configuracion del usuario	
		$purgarQ="SELECT VALOR,FREQ from purga WHERE USER_ID=$id";
		$res=mysql_query($purgarQ,$link);
		$purgarA=mysql_fetch_array($res);
		$valor=$purgarA[0];
		$freq=$purgarA[1];

		if ($valor!=0){
			// Proceso Purga
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

			$res=mysql_query($query,$link);
			$path=$GLOBALS['BKPS_PATH']."/".$id;

			while ($row=mysql_fetch_array($res)){
				if(unlink($path."/".$row['FILENAME'])){
					$delQ="DELETE from backups WHERE USER_ID=".$id." AND ID=".$row['ID'];
					$delRes=mysql_query($delQ,$link);			
				}
			}
			//
		}
		
		//Genero la busqueda de la info del usuario (para obtener los limites)
		$userDetQ="SELECT * FROM user WHERE ID=$id";
		$busca=mysql_query($userDetQ,$link);
		//Genero la consulta en backups para controlar cuanto espacio lleva ocupado
		$espOcpQ="SELECT USER_ID,sum(size)as 'total' FROM backups GROUP BY USER_ID HAVING USER_ID=$id";
		$espRes=mysql_query($espOcpQ,$link);
		if ( $busca ){
			$userQ=mysql_fetch_array($busca);
			$limit=$userQ['MAX_LIMIT'];
			$dlimit=$userQ['DAY_LIMIT'];
			$esp=mysql_fetch_array($espRes);
			$total=$esp['total'];//Total de espacio ocupado en el Servidor
			if ($size > $dlimit || (($size+$total)>$limit)){
				echo "Error: Usuario ha excedido la cuota maxima de espacio";
			}else{
				// Devuelve los datos de conexion para el scp
				echo "0:".$GLOBALS['USER_BKPS']."@".$GLOBALS['IP_SERVER'].":".$GLOBALS['TMP_PATH'];
			}
		}else{
			echo "Error: al procesar la consulta";
		}
	}else{
		echo "Error: No se ha localizado ningun usuario con los datos aportados";
	}
	desconectar($link);
?>