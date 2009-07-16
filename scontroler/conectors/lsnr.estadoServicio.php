<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Estado_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost("servicio"));
	$ret="<servicestatus code='".$servicio->estado()."' />";
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>