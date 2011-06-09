<?php
	/**
	 * md5.convert.php
	 * 
	 * Este servicio es utilizado por los scripts 'install.sh' y 'reconfigure.sh'
	 * Servicio md5convert.php que recibe como parametro un password
	 * retorna el password en md5 concatenado con el simbolo '/'
	 */
	
	echo "/".md5($_GET['text']);
?>