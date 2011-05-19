<?php
	include_once 'includes/adminAuthValidation.php';
	include_once 'includes/headers.php';
	include_once 'includes/libsheader.php';
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Pagina del Administrador de SACSEX</title>

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
	<form action='administrador.php' method='POST'>
		<input type="radio" name="accion" value="auser">Alta de usuario
		<input type="radio" name="accion" value="buser" checked>Baja de usuario
		<input type='submit' value='Seleccionar'  />
	</form>
<!--	<a href='administrador.php' name='ausr'>Alta de Usuario</a>-->
<!--	<a href='administrador.php' name='busr'>Baja de Usuario</a>-->
<?php 
	$accion=$_POST['accion'];
	if ($accion == 'auser' ){
		echo "<form action='alta.php' id='newuser' method='post'>
			Usuario: <input type='text' name='user' /> <br />
			Contraseña: <input type='password' id='pass1' name='pass' />
			Repite Contraseña: <input type='password' id='pass2' name='passver' /><br />
			Tamaño Maximo permitido Diario: <input type='text' name='espDir' /><br />
			Espacio Maximo Assignado: <input type='text' name='espMax' /><br />	
			<input type='button' value='valida' onclick='validarPass()' />
		</form>";
	}else{
		$link=conectar('bdsintesi');
		$query="SELECT ID,NAME FROM user";
		$result=mysql_query($query,$link);
		$rows=mysql_num_rows($result);
		if ($rows >0){
			echo "<table><tr><th>ID Usuario</th><th>Nombre de Usuario</th></tr>";
			while ($row=mysql_fetch_array($result)){
				printf("<tr><td>%d</td><td>%s</td><td><a href=\"bajas.php?id=%d\">Borrar</a></td></tr>",$row["ID"],$row["NAME"],$row["ID"]);
			}
			desconectar($link);
			echo "</table>";
		}else{
			echo "No hi ha cap usuari <br />";
		}
	}

?>


</body>
</html>