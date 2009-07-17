<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Estado_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
//	$servicio = new Servicio($lnsr->doPost('servicio'));
	$servicio = new Servicio('apache3');
	

	
	$infoHost= $servicio->infoHost();

	$memo = $infoHost['memo'];
	$memd = $infoHost['memd'];
	$cpu = $infoHost['disct'];
	$disc = $infoHost['diso'];
	
	$ret.= "$memo , $memd , $cpu , $disc";
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>