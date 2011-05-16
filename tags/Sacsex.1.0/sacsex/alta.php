<?php
include_once 'includes/functions.php';
	
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$pass2=$_POST['passver'];
	$limit=$_POST['espMax'];
	$dlimit=$_POST['espDir'];
	$id=$_POST['id'];
	
	if ($pass==$pass2) {
		$id2=buscaUser($user, $pass);
		if ($id2==''){
			srand(time());
			$id2 = (rand()%9999999)+100000;
			while (!idValido($id2)){
				$id2 = (rand()%9999999)+100000;
			}
			$link=conectar('bdsintesi');
			$query="INSERT into user values ($id2,'$user',MD5('$pass'),0,0,$limit,$dlimit)";
			$result=mysql_query($query,$link);
			desconectar($link);
			$id2=verificaUser($user, $pass);
			echo "<script type='text/javascript'>
				document.location = 'administrador.php';
			</script>";
		}else{
			$error="Ya consta en la base de datos un usuario con los datos introducidos";
		}
	}else{
		$error= "Error: La contrasenya no es identica.";
	}
	echo "<script type='text/javascript'>
				alert('$error');
				document.location = 'form.php?id=$id';
			</script>";
	
	?>