<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Arrancar_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	$servicio->addCmd($lnsr->doPost('nombre'),$lnsr->doPost('cmd'));
	$servicio->guardar();
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>