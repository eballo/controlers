<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Autenticar_Host","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	
	if ($_SESSION['hosts'][$lnsr->doPost('servicio')] ){
		$ret.="<authhost result='ok' />";
	}else{
		$ret.="<authhost result='ko' />";
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>