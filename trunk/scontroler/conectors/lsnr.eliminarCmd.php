<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Eliminar_Cmd_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	if ($_SESSION['hosts'][$lnsr->doPost('servicio').$_SESSION['directorio']] ){
		$servicio = new Servicio($lnsr->doPost('servicio'));
		$servicio->delCmd($lnsr->doPost('nombre'));
		$servicio->guardar();
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>