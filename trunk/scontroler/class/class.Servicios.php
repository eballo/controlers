<?php
/**
 * Representa grupaciones de servicios
 */


class Servicios{

	/**
	 * Retorna un array con servicios , todos los que existen en el sitema
	 * @return Array de Servicio
	 */
	public function getServicios(){

		$dir = opendir("servicios/".$_SESSION['directorio']."/");
		while ($fservicio = readdir($dir))
		{
			$datosf = explode(".",$fservicio) ;
			$servicio = $datosf[0] ;
			$extension = $datosf[1];
				
			if ($extension == "xml" && $servicio != "inf"){
				$servicios[count($servicios)] = new Servicio($servicio);
			}

		}
		closedir($dir);

		return $servicios;
	}

}
?>