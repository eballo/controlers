<?php
//////////////////////////////////////////////////
require_once '../includes/xmlResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Parar_Servicio","XML","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){
	if ($_SESSION['hosts'][$lnsr->doPost('servicio').$_SESSION['directorio']] ){
		$servicio = new Servicio($lnsr->doPost('servicio'));
		$term = new Terminal($servicio->getHost(),$servicio->getUser(),$_SESSION['hosts'][$servicio->getNombre().$_SESSION['directorio']."password"]);
		$term->conectar();
		$ret.="<comando res='".$term->comando($servicio->getCmdParada())."' />";
	}
}

$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>