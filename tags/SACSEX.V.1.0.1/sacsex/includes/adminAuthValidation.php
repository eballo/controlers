<?php

if (! ($_SESSION['login'] && $_SESSION['type'] == "admin")){
		//Si no se ha establecido la sesion o el tipo de usuario no es administrador
		$_SESSION['login'] = false;
		$_SESSION['type'] = "";
		$_SESSION['id'] = $id;

		echo"<script type='text/javascript'>
						document.location = 'login.php?loginError=2';
					</script>";	
}


?>