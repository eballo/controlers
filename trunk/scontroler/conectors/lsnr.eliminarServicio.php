<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Eliminar_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	if ($_SESSION['hosts'][$lnsr->doPost('servicio')] ){
		$servicio = new Servicio($lnsr->doPost('servicio'));
		$servicio->eliminar();
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>