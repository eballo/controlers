<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Guardar_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$servicio = new Servicio($lnsr->doPost('nombre'));
	$servicio->setDescripcion($lnsr->doPost('descripcion'));
	$servicio->setFicheroPid($lnsr->doPost('ficheropid'));
	$servicio->setNombreProceso($lnsr->doPost('nombreproceso'));
	$servicio->setPuerto($lnsr->doPost('puerto'));
	$servicio->setHost($lnsr->doPost('host'));
	$servicio->setUser($lnsr->doPost('user'));
	$servicio->setPassword($lnsr->doPost('password'));
	$servicio->setCmdArranque($lnsr->doPost('cmdarranque'));
	$servicio->setCmdParada($lnsr->doPost('cmdparada'));
	$servicio->setCmdReinicio($lnsr->doPost('cmdreinicio'));
	
	$servicio->guardar();
	$ret ="<guardado result='OK' />";
	
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>