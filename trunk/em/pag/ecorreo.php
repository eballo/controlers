<?php
	require_once '../../emadmin/class/class.Correo.php';
	require_once '../../emadmin/class/class.Dbs.php';	
	require_once '../../emadmin/class/class.Mail.php';	
	require_once '../../emadmin/class/class.phpmailer.php';
	
	$de = $_POST['de'];
	$contenido = $_POST['contenido'];
	
	$correo = new Correo($de , $contenido );
	echo $correo->enviar();
	
?>