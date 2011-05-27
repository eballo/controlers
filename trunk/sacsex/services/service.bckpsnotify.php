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
		//Genero la busqueda de la info del usuario (para obtener los limites)
		$userDetQ="SELECT * FROM user WHERE ID=$id";
		$busca=mysql_query($userDetQ,$link);
		//Genero la consulta en backups para controlar cuanto espacio lleva ocupado
		$espOcpQ="SELECT USER_ID,sum(size)as 'total' FROM backups GROUP BY USER_ID HAVING USER_ID=$id";
		$espRes=mysql_query($espOcpQ,$link);
		if ( $busca ){
			$userQ=mysql_fetch_array($busca);
			$limit=$userQ['LIMIT'];
			$dlimit=$userQ['DAY_LIMIT'];
			$esp=mysql_fetch_array($espRes);
			$total=$esp['total'];//Total de espacio ocupado en el Servidor
			if ($size > $dlimit || (($size+$total)>$limit)){
				echo "Error: Usuario excedio la quota maxima de espacio";
			}else{
				$insQ="INSERT into backups (FILENAME,SIZE,DATE,USER_ID) VALUES ('$file',$size,'$date',$id)";
				$res=mysql_query($insQ,$link);
				if ($res){
					echo "0:Insert Realizado";
				}else{
					echo "Error: No se ha podido hacer el insert";
				}
			}
		}else{
			echo "Error al procesar la consulta";
		}
	}else{
		echo "Error: No se ha localizado ningun usuario con los datos aportados";
	}
	desconectar($link);
?>