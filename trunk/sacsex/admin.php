<?php
	include_once 'includes/adminAuthValidation.php';
	include_once 'includes/headers.php';
	include_once 'includes/libsheader.php';
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SACS_EX. Formulario de Alta de Nuevos Usuarios</title>
<script type="text/javascript">

	function validarPass(){
		$("#pass1").val($.md5($("#pass1").val()));
		$("#pass2").val($.md5($("#pass2").val()));
		$("#newuser").submit();
	}
	function validarKeyEnter( e ){
		if (e.keyCode == 13){
			validarLogin();
		}
	}
</script>
</head>
<body>
<h3> Formulario de Alta de Nuevos Usuarios:
</h3>
<form action='alta.php' id="newuser" method='post'>
	Usuario: <input type="text" name='user' /> <br />
	Contraseña: <input type="password" id="pass1" name='pass' />
	Repite Contraseña: <input type="password" id="pass2" name='passver' /><br />
	Tamaño Maximo permitido Diario: <input type="text" name='espDir' /><br />
	Espacio Maximo Assignado: <input type="text" name='espMax' /><br />	
	<input type='button' value='Seleccionar' onclick="validarPass()" />
</form>
</body>
</html>