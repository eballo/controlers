<?php
include_once 'includes/functions.php';
	
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$pass2=$_POST['passver'];
	$limit=$_POST['espMax'];
	$dlimit=$_POST['espDir'];
	$id=$_POST['id'];
	if ($pass==$pass2) {
		newUser($user,$pass,$limit,$dlimit);
	}else{
		$error= "Error: La contrasenya no es identica.";
	}
	echo "<script type='text/javascript'>
				alert('$error');
				document.location = 'admin.php';
			</script>";
	
	?>