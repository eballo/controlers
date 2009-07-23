<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Eliminar_Cmd_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	$servicio->delCmd($lnsr->doPost('nombre'));
	$servicio->guardar();
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>