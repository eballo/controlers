<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Autenticar_Host","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	$servicio = new Servicio($lnsr->doPost('servicio'));
	
	if ($servicio->getPassword() == $lnsr->doPost('password')){

		$_SESSION['hosts'][$lnsr->doPost('servicio').$_SESSION['directorio']] = true;
		$_SESSION['hosts'][$lnsr->doPost('servicio').$_SESSION['directorio']."password" ] = base64_decode($lnsr->doPost('lp'));
		$ret.="<authhost result='ok' />";
	}else{
		$ret.="<authhost result='ko' />";
		
		$_SESSION['hosts'][$lnsr->doPost('servicio').$_SESSION['directorio']] = false;
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>