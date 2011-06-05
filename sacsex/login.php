<?php
include_once 'includes/headers.php';

$link=conectar($GLOBALS['$MYSQL_BDNAME']);

//Inicializacion variables
if (isset($_GET['loginError'])){
	$loginError=$_GET['loginError'];
}else{
	$loginError="";
}
if ( isset ($_POST['user']) && isset($_POST['pass']) && isset($_POST['login'])){
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$login=$_POST['login'];
}else{
	$user="";
	$pass="";
	$login="";
}
if ($login == "true"){
	$id=verificaUser($user, $pass,$link);
	if ($id != "") {
		if (esAdmin($id,$link)){
			//Recogemos los valores de las variables de sesion para admin
			$_SESSION['login'] = true;
			$_SESSION['type'] = "admin";
			$_SESSION['id'] = $id;
				
			echo"<script type='text/javascript'>
						document.location = 'admin.php';
					</script>";
		}else{
			//Recogemos los valores de las variables de sesion para el usuario
			$_SESSION['login'] = true;
			$_SESSION['type'] = "user";
			$_SESSION['id'] = $id;
				
			echo"<script type='text/javascript'>
						document.location = 'search.php';
					</script>";
		}
	}else{
		//Seteamos los valores de sesion a los valores por defecto
		$_SESSION['login'] = false;
		$_SESSION['type'] = "";
		$_SESSION['id'] = "";

		echo"<script type='text/javascript'>
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
	<div class='body'>
		<div class='mainContainer'>
			<div class='loginContainer'>
				<form id='login' action='login.php' method='POST'>
				<input type='hidden' name='login' value='true' />
					<table>
						<tr>
							<td><b>Login Sacsex</b></td>
							<td>
								<div id='divErrors' style='color: red'>
										<?php 
										if ( $loginError ){
											switch ( $loginError){
												case 1:
													echo "Acceso restringido para usuarios.";
													break;
												case 2:
													echo "Acceso restringido para administradores.";
													break;
												case 3:
													echo "Login no valido.";
													break;
											}
										}
										?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Usuario: </td>
							<td><input type='text' name='user' /></td>
						</tr>
						<tr>
							<td>Password: </td>
							<td><input id='passwordForm' type='password' name='pass' /> </td>
						</tr>
						<tr>
							<td colspan="2"><input class="botonForm" type='button' value='valida' onclick="validarLogin()" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
</body>
</HTML>
<?php 
		desconectar($link);
?>