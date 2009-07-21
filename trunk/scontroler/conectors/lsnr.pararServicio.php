<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Parar_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	$term = new Terminal($servicio->getHost(),$servicio->getUser(),$servicio->getPassword());
	$term->conectar();
	$ret.="<comando res='".$term->comando($servicio->getCmdParada())."' />";
//	$ret.="<comando res='".$servicio->getCmdParada()."' />";
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>