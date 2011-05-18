<?php
include_once './includes/headers.php';

$user=$_POST['user'];
$pass=$_POST['pass'];
$login=$_POST['login'];

if ($login == "true"){
	$id=verificaUser($user, $pass);
	if ($id != "") {
		if (esAdmin($id)){
			//TODO Comentar
			$_SESSION['login'] = true;
			$_SESSION['type'] = "admin";
			$_SESSION['id'] = $id;
				
			echo"<script type='text/javascript'>
						document.location = 'admin.php';
					</script>";
		}else{
			//TODO Comentar
			$_SESSION['login'] = true;
			$_SESSION['type'] = "user";
			$_SESSION['id'] = $id;
				
			echo"<script type='text/javascript'>
						document.location = 'search.php';
					</script>";
		}
	}else{
		//TODO Comentar
		$_SESSION['login'] = false;
		$_SESSION['type'] = "";
		$_SESSION['id'] = "";

		echo"<script type='text/javascript'>
			alert();
				document.location = 'login.php?loginError=3';
			</script>";
	}

}
?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Proyecto De Credito de Sintesi SACSEX</title>
<?php  include_once 'includes/libsheader.php';?>
<script type="text/javascript">

	$(document).ready(function() {
		   $('input[type="text"] , input[type="password"]').click(function(){
			   $(this).val("");
			});
	});

	function validarLogin(){
		$("#passwordForm").val($.md5($("#passwordForm").val()));
		$("#login").submit();
	}
	function validarKeyEnter( e ){
		if (e.keyCode == 13){
			validarLogin();
		}
	}
</script>
</head>
<body onkeypress="validarKeyEnter( event )">
<div id='divErrors' style='color: red'>
<?php 
if ( $_GET['loginError'] ){
	switch ($_GET['loginError']){
		case 3:
			echo "Login no valido.";
			break;
		case 1:
			echo "Acceso restringido para usuarios.";
			break;
		case 2:
			echo "Acceso restringido para administradores.";
			break;
	}
}
?></div>
	<form id='login' action='login.php' method='POST'>
		<input type='text' name='user' />
		<input id='passwordForm' type='password' name='pass' /> 
		<input type='hidden' name='login' value='true' />
		<input type='button' value='valida' onclick="validarLogin()" />
	</form>
</body>
</HTML>
