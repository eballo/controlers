<?php
	//TODO Comentar todas las funciones con el mismo formato
	//Funciones de validacion de datos

	require_once 'Archive/Tar.php';
					
	function readTar($nombre){
		$obj = new Archive_Tar($nombre); // name of archive
		$files = $obj->listContent();       // array of file information
	return $files;
	}
	
	function esNumero($val){
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
	
	/**
	 * Funcion para convertir megabytes a kilobytes
	 * @param Entero $num valor en megabytes
	 * @return Entero $num en kilobytes
	 */
	function megastokas($num){
		return $num*1024;
	}

	/**
	 * Funcion para convertir kilobytes a megabytes
	 * @param Entero $num valor en kilobytes
	 * @return Entero $num en megabytes
	 */
	function kastomegas($num){
		return $num/1024;
	}
	
	/**
	 * Función para convertir megabytes a gigabytes
	 * @param Entero $num valor en megabytes
	 * @return Entero $num en gigabytes
	 */
	function megasToGiga($num){
		return ($num/1024)/1024;
	}
	
	function fechaEsp($fecha){
		list($date, $time) = explode(' ', $fecha); //Separamos fecha y hora
		list($y, $M, $d) = explode('-', $date);
		list($h, $m, $s) = explode(':',$time);
	}
	
//	/**
//	 * Función 
//	 * @param unknown_type $fecha1
//	 * @param unknown_type $freq
//	 * @param unknown_type $num
//	 * @return number
//	 */
//	function comparafechas($fecha1,$freq,$num){
//		$hoy=getdate();
//		$hoy=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];
//		
//		$datetime1 = new DateTime($fecha1);
//		$datetime2 = new DateTime($hoy);
//		$interval = $datetime1->diff($datetime2);
//		if($freq=='mes'){
//			$dias=$num*30;
//		}elseif($freq=='anyos'){
//			$dias=$num*365;
//		}else{
//			$dias=$num;
//		}
//		$total=$interval->format('%a');
//		
//		if (($total-$dias)>0){
//			return 1;
//		}elseif(($total-$dias)<0){
//			return -1;
//		}else{
//			return 0;
//		}
//	}

	//Funciones de acceso a Base de Datos
	
	/**
	 * Función para establecer la conexión con la base de datos
	 * @param String $db nombre de la base de datos
	 * @return $link enlace de conexion con base de datos
	 */
	function conectar($db){
		
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
	
	/**
	 * Función para terminar correctamente la conexión con la base de datos
	 * @param $link enlace de conexión
	 */
	function desconectar($link){

		mysql_close($link);
	}
	
	/**
	 * Función para buscar un usuario en la BD
	 * @param String $user nombre de usuario
	 * @param $link enlace de conexión
	 * @return String $id de usuario si se encontro
	 * 		   null si no se encontro usuario
	 */
	function buscaUser($user , $link){

		$query="SELECT ID FROM user WHERE NAME='$user'";
		$busca=mysql_query($query,$link);
		$idA=mysql_fetch_array($busca);
		$id=$idA[0];	
		return $id;
	}
	
	/**
	 * Función para validar si datos del usuario son correctos
	 *  validando mediante busqueda de su $id y comprobando si $passwordlogin
	 *  es identico al PASSWORD almacenado en la base de datos
	 * @param String $user nombre de usuario
	 * @param String $passwordLogin password login md5 del usuario 
	 * @param $link enlace de conexión
	 * @return String $id de usuario si el usuario es valido
	 * 		   null si el usuario no es valido
	 */
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
	
	/**
	 * Función para comprobar si el nuevo id de usuario a asignar no pertenece a otro
	 * @param String $id de usuario
	 * @param $link enlace de conexión
	 * @return 0 o cierto si el nuevo $id puede ser asignado	 
	 */
	function idValido($id , $link){

		$query="SELECT * FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_num_rows($busca);
		
		return $a==0;
	}
	
	/**
	 * Función para saber si el $id pertenece al Administrador 
	 * @param String $id del usuario
	 * @param $link enlace de conexión
	 * @return 1 si el $id pertenece al Administrador
	 */
	function esAdmin($id , $link){

		$query="SELECT ADMIN FROM user WHERE ID=$id";
		$busca=mysql_query($query,$link);
		$a=mysql_fetch_array($busca);
		$a=$a[0];
		
		return $a==1;
	}
	
	/**
	 * Función de inserción de nuevo usuario
	 * @param String $user nombre de usuario
	 * @param String $pass password de usuario
	 * @param Entero $limit espacio limite total
	 * @param Entero $dlimit espacio limite por dia
	 * @param $link enlace de conexión 
	 * @return String $res null si la insercion fue correcta
	 */
	function newUser($user,$pass,$limit,$dlimit,$link){

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
				// modifico la mascara para que por defecto el usuario del grupo tambien pueda escribir
				//umask(octdec("0002"));				
				// La carpeta shared debe existir y tener permisos 2777 
				// ademas de pertenecer al grupo sacs. El usuario creado exclusivamente
				$a=$GLOBALS['BKPS_PATH'];
				echo "A".$a;
				$newdir=$GLOBALS['BKPS_PATH']."/".$id;
				echo "ne".$newdir;
				if(!mkdir("$newdir")){
					$res='Error al crear la carpeta';
//				}else{		//TODO fg
//					$enlace="/home/sacs/bkps/".$id;	
//					// Al igual que la ruta de $newdir, la ruta /home/sacs/bkps Debe existir y tener los
//					// mismos permisos establecidos  
//			     	if(symlink($newdir,$enlace)){
//			     		echo "<script type='javascript'>
//							alert('Creado el enlace');
//						</script>";
//			     	}else{
//			     		$res='Error al crear el enlace';
//			     		rmdir($newdir);
//			     	}
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
			$error="Error: No se ha podido eliminar el usuario con id $id.";
		}else{
			$directorio=$GLOBALS['BKPS_PATH']."/".$id;
			if(eliminaRecursivo($directorio)==0){
				$error='';
//				$dir="/home/sacs/bkps/".$id; //TODO Enlace then?
				// unlink --> elimina ficheros o lo que sea!! diferente de directorio
//				if(!unlink($dir)){
//					$error="Error al eliminar el enlace";
//				}else{
//					$error='';
//				}
			}else{
				$error="Error al eliminar el directorio";
			}			
		}
		return $error;
	}
	
	
	/**
	 * Función para eliminar un backup
	 * Lo elimina de la base de datos y del directorio de backups
	 * @param Entero $idf	identificador del tar backup
	 * @param Entero $id	identificador del usuario	
	 * @param $link 		enlace de conexión
	 */
	function eliminaBackup($idf,$id,$link){

		$nomQ="SELECT FILENAME from backups WHERE ID=$idf AND USER_ID=$id";
		$buscaN=mysql_query($nomQ,$link);
		$nomRes=mysql_fetch_array($buscaN);
		$nomRes=$nomRes[0];
		$delQuery = "DELETE FROM backups WHERE ID='$idf' AND USER_ID=$id";
		$res=mysql_query($delQuery,$link);
		if ($res){
			//Si lo encuentro, lo elimino tambien del Servidor
			$ruta=$GLOBALS['BKPS_PATH']."/".$id."/".$nomRes; //Hay que perdir como sera el nombre real.

			if(unlink($ruta)){	// Va mal el unlink, y no se porque?¿, no borra el tar fisico
				$error='';
			}else{
				$error="Error al eliminar el archivo";
			}
		}else{
			$error="Error al eliminar el archivo";
		}
	}
?>

