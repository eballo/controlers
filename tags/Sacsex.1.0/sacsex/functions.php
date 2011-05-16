<?php
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
	
	
	//Funciones de acceso a Base de Datos
	
	function conectar($db){
		/*
		 * Establece la conexion a la base de datos
		 * y devuelve el 'enlace' para trabajar en esta situandose en la base de datos $db
		 */
		$link=mysql_connect("172.20.1.96","root","sadiel");
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
	function buscaUser($user){
		/*
		 * Busca un Usuario en la BD y devuelve su id
		 */
		$link=conectar('bdsintesi');
		$query="SELECT ID FROM user WHERE NAME='$user'";
		$busca=mysql_query($query,$link);
		$idA=mysql_fetch_array($busca);
		$id=$idA[0];
	 	desconectar($link);	 	
		return $id;
	}
	
	//function verificaUser($user,$pass){
	function verificaUser($user , $passwordLogin){
		$id=buscaUser($user);		
		$link=conectar('bdsintesi');
		$query="SELECT PASSWORD FROM user WHERE ID='$id'";
		$busca=mysql_query($query,$link);
		$passwordA=mysql_fetch_array($busca);
		$password = $passwordA[0];
	 	desconectar($link);
	 	if ($password == $passwordLogin ){
	 		return $id;
	 	}else{
			return "";
	 	}
	}
	function idValido($id){
		/*
		 * devuelve Cierto si el nuevo id de usuario a asignar no consta 
		 * como asignado a otro usuario  
		 */
		$link=conectar('bdsintesi');
		$query="SELECT * FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_num_rows($busca);
		
		return $a==0;
	}
	function esAdmin($id){
		/*
		 * Devuelve Cierto si el id usuario pertenece a un Administrador 
		 */
		$link=conectar('bdsintesi');
		$query="SELECT ADMIN FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_fetch_array($busca);
		$a=$a[0];
		
		return $a==1;
	}
?>

