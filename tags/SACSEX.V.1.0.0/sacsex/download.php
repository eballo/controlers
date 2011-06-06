<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	/**
	 * Validamos que exista el identificador del fichero
	 */
	if (isset($_GET['idfile'])){
		$idFile=$_GET['idfile'];
		$uid=$_SESSION['id'];
		$link=conectar($GLOBALS['MYSQL_BDNAME']);
		$fileQ="SELECT filename FROM backups WHERE ID=".$idFile." AND USER_ID=".$uid;
		$fileName = mysql_query($fileQ,$link);
		/**
		 * Si retorna un nombre de fichero es por que existe y pertenece a el usuario que lo solicita
		 */
		$fileNameA =mysql_fetch_array($fileName);
		$file =$fileNameA[0];
		if ($idFile != ""){
			$path=$GLOBALS['BKPS_PATH']."/".$uid."/".$file;
			/**
			 * Validamos que exista el fichero en el disco
			 */
			if (is_file($path)) {
				/**
				 * Si existe, recogemos el tamaño del archivo y el tipo
				 */
				$size = filesize($path);	
				if (function_exists('mime_content_type')) {
					$type = mime_content_type($path);
				} else if (function_exists('finfo_file')) {
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $path);
					finfo_close($info);
				}
				if ($type == '') {
					$type = "application/force-download";
				}
				// Definir headers
				header("Pragma: public"); // required
			    header("Expires: 0");
			    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			    header("Cache-Control: private",false); // necesario para algunos navegadores
			    header("Content-Type: $type");
			    header("Content-Disposition: attachment; filename=\"".$file."\";" );
			    header("Content-Transfer-Encoding: binary");
			    header("Content-Length: ".$size);
			    ob_clean(); //limpiamos los buffer
			    flush(); 
				
				readfile($path);
			} else {
				echo("El archivo no existe.");
			}
		}else{
			echo "Error el fichero no existe o no tiene acceso";
		}	
	}else{
		echo "Falta parametro identificador del fichero";
	}
	
?>