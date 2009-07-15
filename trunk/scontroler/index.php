<html>
<head></head>
<body>
<form action="conectors/lsnr.guardarServicio.php" method="POST">
<table>
	<tr>
		<td>Host</td>
		<td><input type="text" name="host"></td>
	</tr>
	<tr>
		<td>Usuario</td>
		<td><input type="text" name="user"></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="password"></td>
	</tr>
</table>
<hr>
<table>
	<tr>
		<td>Nombre</td>
		<td><input type="text" name="nombre"></td>
	</tr>
	<tr>
		<td>Descripción</td>
		<td><textarea name="descripcion"> </textarea></td>
	</tr>
	<tr>
		<td>Nombre Proceso</td>
		<td><input type="text" name="nombreproceso"></td>
	</tr>
	<tr>
		<td>Puerto del servicio</td>
		<td><input type="text" name="puerto"></td>
	</tr>
	<tr>
		<td>Fichero PID</td>
		<td><input type="text" name="ficheropid"></td>
	</tr>
	<tr>
		<td>Comando Arranque</td>
		<td><input type="text" name="cmdarranque"></td>
	</tr>
	<tr>
		<td>Comando Reinicio</td>
		<td><input type="text" name="cmdparada"></td>
	</tr>
	<tr>
		<td>Comando Parada</td>
		<td><input type="text" name="cmdreinicio"></td>
	</tr>
</table>
<input type='submit'>
</form>
</body>
</html>
