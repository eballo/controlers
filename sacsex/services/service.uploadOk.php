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

	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$file=$_GET['file'];
	$size=$_GET['size'];
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	//busco el ID del usuario
	$id=verificaUser($user, $pass, $link);
	if ( $id!=''){
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