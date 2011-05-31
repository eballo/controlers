<?php
	// Servicio para retornar password de usuario en md5 concatenado 
	//  con "/" al instalar la aplicación por el script preinstall.php
	
	echo "/".md5($_GET['text']);
?>