<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Autenticar_Host","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	
	if (md5($servicio->getPassword()) == $lnsr->doPost('password')){
		$ret.="<authhost result='ok' />";
		
		$_SESSION['hosts'][$lnsr->doPost('servicio')] = true;
		
	}else{
		$ret.="<authhost result='ko' />";
		
		$_SESSION['hosts'][$lnsr->doPost('servicio')] = false;
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>