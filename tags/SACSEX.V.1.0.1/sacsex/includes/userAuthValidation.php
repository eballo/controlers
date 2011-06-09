<?php

if (! ($_SESSION['login'] && $_SESSION['type'] == "user")){
		//Si no se estableci� sesion o el usuario no es del tipo user
		$_SESSION['login'] = false;
		$_SESSION['type'] = "";
		$_SESSION['id'] = $id;

		echo"<script type='text/javascript'>
						document.location = 'login.php?loginError=1';
					</script>";
}

?>