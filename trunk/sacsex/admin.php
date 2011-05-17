<?php
	include_once 'includes/adminAuthValidation.php';
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SACS_EX. Formulario de Alta de Nuevos Usuarios</title>
</head>
<body>
<h3> Formulario de Alta de Nuevos Usuarios:
</h3>
<form action='alta.php' method='post'>
	Usuario: <input type="text" name='user' /> <br />
	Contraseña: <input type="password" name='pass' />
	Repite Contraseña: <input type="password" name='passver' /><br />
	Tamaño Maximo permitido Diario: <input type="text" name='espDir' /><br />
	Espacio Maximo Assignado: <input type="text" name='espMax' /><br />	
	<input type="submit" value='valida' />
</form>
</body>
</html>