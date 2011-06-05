<?php
	/**
	 * auth.php
	 * 
	 * Este servicio es utilizado por el script 'install.sh'
	 * Servicio auth.php que recibe como parametros el usuario, password en md5 y un boleano para instalar 
	 * Comprueba si existe el usuario en la base de datos y si tiene instalada la aplicación.
	 * Campo INSTALL de la tabla user
	 * 		0 --> No tiene instalada la aplicación
	 *		1 --> Tiene instalada la aplicación
	 * Retorna '2/$id' si la aplicación se ha podido instalar
	 */
	
	include_once '../includes/servicesHeaders.php';

	//Conexion base de datos
	$link=conectar($GLOBALS['$MYSQL_BDNAME']);
	
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	$install=$_GET['install'];
	$id=verificaUser($user, $pass,$link);
	$resp=3;
		
	if ($install=="true"){
		if ( $link){
			if ($id!=""){
				$query="SELECT INSTALAT from user where ID=$id";
				$busca=mysql_query($query,$link);
				$isinstallA=mysql_fetch_array($busca);
				$isinstall=($isinstallA[0])+2; //En la tabla, 0 indica que no esta instalado. 1 que si lo esta
				$resp="$isinstall:$id";
			}else{
				$resp="3:NoValido";
			}
			echo $resp;
		}else{
			echo "No se establecio conexion";
		}
	}else{
		if ( $id != ''){
			echo ":".$id.":".$GLOBALS['TMP_PATH'];
		}else{
			echo ":1";
		}
	}
	
	desconectar($link);
?>