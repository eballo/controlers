<?php
	//
	//Funciones de validacion de datos
	
	function esNumero($val){
		/*
		 * Comprueba que tipo de valores contiene la variable recibida $val
		 * devuelve si es o no un valor numérico (con float)
		 * */
		$a=preg_match_all("/[\D]/",$val,$lista); //Numero de veces que aparece un valor no numerico
		$b=0;
		if ( $a >0 ){
			$b=preg_match("/./",$val,$lista);
		}
		$a=$a-$b;
		return $a==0;
	}
	
	function esEntero($val){
		return preg_match_all("/[\D]/",$val,$l)==0;
	}
	
	/**
	 * Enter description here ...
	 * @param String $text Texto a validar
	 * @param String $tipo Numerico o Cadena de texto
	 * @return Valor style del elemento
	 */
	function validarInput( $text , $tipo){
		
		if ($tipo=='string'){
			if ($text!='' && strlen($text)<50){
				$valido=true;
			}else{
				$valido=false;
			}
		}else{
			$valido=esEntero($text);
		}
		return $valido;
	}
	
	//Funciones de acceso a Base de Datos
	
	function conectar($db){
		/*
		 * Establece la conexion a la base de datos
		 * y devuelve el 'enlace' para trabajar en esta situandose en la base de datos $db
		 */
		$ip=$GLOBALS['MYSQL_IP'];
		$user=$GLOBALS['MYSQL_USER'];
		$pass=$GLOBALS['MYSQL_PASSWORD'];
		//$link=mysql_connect($MYSQL_IP,$MYSQL_USER,$MYSQL_PASSWORD);
		// TODO Buscar form mas simple, si la hay
		$link=mysql_connect($ip,$user,$pass);
		if ( ! $link ){
			echo "Error al intentar conectar a mysql";
		}else{
			mysql_select_db($db);
		}
		
		return $link;
	}
	
	function desconectar($link){
		/*
		 * Cierra sesion de mysql en el enlace recibido por parametro
		 */
		mysql_close($link);
	}
	function buscaUser($user , $link){
		/*
		 * Busca un Usuario en la BD y devuelve su id
		 */
		$query="SELECT ID FROM user WHERE NAME='$user'";
		$busca=mysql_query($query,$link);
		$idA=mysql_fetch_array($busca);
		$id=$idA[0];	
		return $id;
	}
	
	function verificaUser($user , $passwordLogin , $link){
		$id=buscaUser($user,$link);		
		$query="SELECT PASSWORD FROM user WHERE ID='$id'";
		$busca=mysql_query($query,$link);
		$passwordA=mysql_fetch_array($busca);
		$password = $passwordA[0];
	 	if ($password == $passwordLogin ){
	 		return $id;
	 	}else{
			return "";
	 	}
	}
	function idValido($id , $link){
		/*
		 * devuelve Cierto si el nuevo id de usuario a asignar no consta 
		 * como asignado a otro usuario  
		 */
		$query="SELECT * FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_num_rows($busca);
		
		return $a==0;
	}
	function esAdmin($id , $link){
		/*
		 * Devuelve Cierto si el id usuario pertenece a un Administrador 
		 */
		$query="SELECT ADMIN FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_fetch_array($busca);
		$a=$a[0];
		
		return $a==1;
	}
	function newUser($user,$pass,$limit,$dlimit,$link){
		/*
		 * Funcion de Inserci�n de nuevos usuarios
		 */
		
		$id=buscaUser($user,$link);
		if ($id==''){
			srand(time());
			$id = (rand()%9999999)+100000;
			while (!idValido($id,$link)){
				$id = (rand()%9999999)+100000;
			}
			$query="INSERT into user values ($id,'$user','$pass',0,0,$limit,$dlimit)";
			$result=mysql_query($query,$link);
			return "";
		}else{
			return "Ya consta en la base de datos un usuario con los datos introducidos";
		}
	}
?>

