<?php
session_start();
if (! ($_SESSION['login'] && $_SESSION['type'] == "user")){
		//TODO Comentar
		$_SESSION['login'] = false;
		$_SESSION['type'] = "";
		$_SESSION['id'] = $id;

		echo"<script type='text/javascript'>
						document.location = 'login.php?loginError=1';
					</script>";
}

?>