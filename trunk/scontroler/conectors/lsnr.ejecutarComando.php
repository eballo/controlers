<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Estado_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	$term = new Terminal($servicio->getHost(),$servicio->getUser(),$servicio->getPassword());
	$term->conectar();
	$ret.="<comando res='".$term->comando($lnsr->doPost('comando'))."' />";
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>