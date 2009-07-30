<?php
/**
 * Motor de lectura de directorios para Scontroler
 **/

class Directorios{

	/**
	 * Retorna un array con directorios
	 */
	public static function leer(){

		$dir = opendir("servicios");
		while ($fdirectorio = readdir($dir))
		{
			if (is_dir("servicios/".$fdirectorio)){
				
				if (file_exists("servicios/".$fdirectorio."/inf.xml")){
					$xml = simplexml_load_file("servicios/".$fdirectorio."/inf.xml");
					$directorios[] = new Directorio($fdirectorio , $xml->proyecto['nombre'] , $xml->proyecto['descripcion'], $xml->proyecto['icono'] );

				}
			}
			
		}
		closedir($dir);

		return $directorios;
	}
}
?>