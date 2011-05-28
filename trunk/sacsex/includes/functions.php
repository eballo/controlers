<?php
	//TODO Comentar todas las funciones con el mismo formato
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
	 * Funcion que sirve para controlar el valor recibido segun sea el tipo de dato
	 * @param String $text Texto a validar
	 * @param String $tipo Numerico o Cadena de texto
	 * @return Cierto o Falso segun sea valido o no
	 */
	function validarInput( $text , $tipo){
		if ($tipo=='string' ){
			if ($text!='' && strlen($text) != 0){
				$valido=true;
			}else{
				$valido=false;
			}
		}else{
			if (esEntero($text) && strlen($text) != 0){
				$valido=true;
			}else{
				$valido=false;
			}
		}
		return $valido;
	}
	
	function megastokas($num){
		return $num*1024;
		
	}
	function kastomegas($num){
		return $num/1024;
	}
	
	function comparafechas($fecha1,$freq,$num){
		$hoy=getdate();
		$hoy=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];
		
		$datetime1 = new DateTime($fecha1);
		$datetime2 = new DateTime($hoy);
		$interval = $datetime1->diff($datetime2);
		if($freq=='mes'){
			$dias=$num*30;
		}elseif($freq=='anyos'){
			$dias=$num*365;
		}else{
			$dias=$num;
		}
		$total=$interval->format('%a');
		
		if (($total-$dias)>0){
			return 1;
		}elseif(($total-$dias)<0){
			return -1;
		}else{
			return 0;
		}
			
	}
	function megasToGiga($num){
		return ($num/1024)/1024;
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
		//TODO Buscar form mas simple, si la hay
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
		$res= "";
		$id=buscaUser($user,$link);
		if ($id==''){
			srand(time());
			$id = (rand()%9999999)+1000000;
			while (!idValido($id,$link)){
				$id = (rand()%9999999)+1000000;
			}
			$query="INSERT into user values ($id,'$user','$pass',0,0,$limit,$dlimit)";
			$result=mysql_query($query,$link);
		
			if ($result == ""){
				$res="Problema insertando el usuario en la base de datos";
			}else{
				umask(octdec("0002"));// modifico la mascara para que por defecto el usuario del grupo tambien pueda escribir
				$newdir="/var/www/sacsex/shared/".$id;
				if(!mkdir("$newdir")){
					echo "<script type='javascript'>
						alert('Error al crear la carpeta');
					</script>";
				}else{		
					$enlace="/home/sacs/bkps/".$id;			
					if ($_SERVER['WINDIR'] || $_SERVER['windir']) {
				     	$res=exec('junction "' . $id . '" "' . $newdir . '"');				     	
				   	} else {
				     	if(symlink($newdir,$enlace)){
				     		echo "<script type='javascript'>
								alert('Creado el enlace');
							</script>";
				     	}else{
				     		echo "<script type='javascript'>
								alert('Error al crear el enlace');
							</script>";
				     		rmdir($newdir);
				     	}					
					}
					
				}
			}
			
		}else{
			$res= "Ya consta en la base de datos un usuario con los datos introducidos";
		}
		
		return $res;
		
	}
	function eliminaRecursivo($dir) {
		//Si es un directorio
		$error=0;
		if (is_dir($dir)) {
			$cont=scandir($dir); //Recojemos todo el contenido del directorio
			foreach ($cont as $elem) {
				//Por cada elemento del contenido, si no es el . o los ..
				if ($elem != "." && $elem != "..") {
					//generamos la nueva ruta
					$ruta=$dir."/".$elem;
					if (is_dir($ruta)){
						//si es un directorio volvemos a llamar a la funcion
						$error=$error+eliminaRecursivo($ruta); 
					}else{
						//si no lo es, intentamos eliminar el archivo (o lo que sea)
						if(!unlink($ruta)){
							$error=$error+1;
						}
					}
				}
			}
			unset($cont);//reseteamos la array de contenidos e intentamos eliminar el directorio
			if(!rmdir($dir)){
				$error=$error+1;
			}
		}
		return $error;
	}
	
	function bajaUser($id, $link){
		$query="delete from user where ID = $id";
	   	$x=mysql_query($query,$link);
		if ($x!=1){
			$errors ="Error: No se ha podido eliminar el usuario con id $id.";
		}else{
			
			echo "Aqui!";
			$directorio="shared/".$id;
			if(eliminaRecursivo($directorio)==0){
				$dir="/home/sacs/bkps/".$id;
				if(!unlink($dir)){
					$error="Error al eliminar el enlace";
				}else{
					$error='';
				}
			}else{
				$error="Error al eliminar el directorio";
			}			
		}
		return $error;
	}
?>

