<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	
	/**
	 * Validamos que exista el identificador del fichero
	 */
	if (isset($_GET['idfile'])){
		$link=conectar('bdsintesi');
		$fileName = mysql_query("SELECT filename FROM backups WHERE ID=".$_GET['idfile']." AND USER_ID=".$_SESSION['id'],$link);

		/**
		 * Si retorna un nombre de fichero es por que existe y pertenece a el usuario que lo solicita
		 */
		$fileNameA = $passwordA=mysql_fetch_array($fileName);
		$file =$fileNameA[0];
		
		if ($file != ""){
			
			$path=$GLOBALS['BKPS_PATH']."/".$_SESSION['id']."/".$file;
			
			/**
			 * Validamos que exista el fichero en el disco
			 */
			if (is_file($path)) {
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
				header("Content-Type: $type");
				header("Content-Disposition: attachment; filename=$file");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $size);
				// Descargar archivo
				readfile($path);
			} else {
				echo "hola";
				die("El archivo no existe.");
			}
		}else{
			echo "Error el fichero no existe o no tiene acceso";
		}	
	}else{
		echo "Falta parametro identificador del fichero";
	}
?>