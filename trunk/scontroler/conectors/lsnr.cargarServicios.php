<?php
//////////////////////////////////////////////////
require_once '../includes/textResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Cargar_Servicios","TEXT","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$servicios = new Servicios();
	$serviciosArray = $servicios->getServicios();
	
	foreach ($serviciosArray as $serv ){
		$ret.="Nombre:".$serv->getNombre()."<br>";
	}

	
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>