<?php 
	include_once 'includes/headers.php';
	if ($_SESSION['id'] != "" && $_SESSION['login']) {
		if ($_SESSION['type'] == "admin"){
			echo"<script type='text/javascript'>
						document.location = 'admin.php';
					</script>";
		}else{
			echo"<script type='text/javascript'>
						document.location = 'search.php';
					</script>";
		}
	}else{
		echo"<script type='text/javascript'>
						document.location = 'login.php';
					</script>";
	}

?>