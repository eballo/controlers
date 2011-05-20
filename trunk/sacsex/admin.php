<?php
include_once 'includes/headers.php';
include_once 'includes/adminAuthValidation.php';
include_once 'includes/libsheader.php';

//Conexion base de datos
$adminLink=conectar('bdsintesi');

$errors="";

if (isset($_GET['action'])){
	$accion=$_GET['action'];
	
	if ( $accion == "addUser"){
		
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		$pass2=$_POST['passver'];
		$limit=$_POST['espMax'];
		$dlimit=$_POST['espDir'];
		
		
		
		if ($pass==$pass2) {
			$errors= newUser($user,$pass,$limit,$dlimit,$adminLink);
		}else{
			$errors= "Error: La contrasenya no es identica.";
		}
	}elseif ($accion == "delUser"){
	   $id=$_GET['id']; 
	   $query="delete from user where ID = $id";
	   $x=mysql_query($query,$adminLink);
		if ($x!=1){
			$errors ="Error: No se ha podido eliminar el usuario con id $id.";
		}
	}
}else{
	$accion="";
}

//Usuarios de la bbdd
$query="SELECT ID,NAME FROM user";
$result=mysql_query($query,$adminLink);
$rows=mysql_num_rows($result);


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
</script>

</head>
<body>
<div class="adminMainContainer">
<div class='addUserFormContainer'>
<form action='admin.php?action=addUser' id='newuser' method='post'>
Usuario: <input class='<?php echo $inputErrors["user"];?>' type='text' name='user' /> <br />
Contraseña: <input class='<?php echo $inputErrors["password"];?>'  type='password' id='pass1' name='pass' /> Repite
Contraseña: <input  class='<?php echo $inputErrors["password"];?>'  type='password' id='pass2' name='passver' /><br />
Tamaño Maximo permitido Diario: <input class='<?php echo $inputErrors["espDir"];?>'  type='text' name='espDir' /><br />
Espacio Maximo Assignado: <input class='<?php echo $inputErrors["espMax"];?>'  type='text' name='espMax' /><br />
<input type='button' value='valida' onclick='validarPass()' /></form>
</div>
<div><?php 
if ($rows >0){
	echo "<table><tr><th>ID Usuario</th><th>Nombre de Usuario</th></tr>";
	while ($row=mysql_fetch_array($result)){
		echo "<tr>".
		"<td>" . $row["ID"] . "</td>".
		"<td>" . $row["NAME"] ."</td>";
		
		if (!esAdmin($row["ID"], $adminLink)){
			echo "<td><a href='admin.php?action=delUser&id=".$row["ID"]."' >Borrar</a></td>";
		}else{
			echo "<td>Bloqueado</td>";
		}
		echo "</tr>";
		}
	echo "</table>";
}else{
	echo "No hi ha cap usuari <br />";
}
?></div>
</div>
</body>
<script type="text/javascript">
	<?php 
		//Zona de errores
		if ($errors != ""){
			echo "alert('$errors');";
		}
		desconectar($adminLink);
	?>
</script>
</html>
