<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Estado_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	$term = new Terminal($servicio->getHost(),$servicio->getUser(),$_SESSION['hosts'][$servicio->getNombre()."password"]);
	$term->conectar();
	
	
	$ret.="<comando>".htmlentities(nl2br($term->comando($lnsr->doPost('comando'))))."</comando>";
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>