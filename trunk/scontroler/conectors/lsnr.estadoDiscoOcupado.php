<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Estado_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));

	$disco = $servicio->estadoDiscoOcupado();
	
	$ret.= $disco;
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>