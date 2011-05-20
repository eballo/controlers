<?php
include_once 'includes/headers.php';
include_once 'includes/adminAuthValidation.php';
include_once 'includes/libsheader.php';

//Conexion base de datos
$adminLink=conectar('bdsintesi');

//Inicializacion de bbdd
$errors="";
$user="";
$pass="";
$pass2="";
$dlimit="";
$limit="";

if (isset($_GET['action'])){
	$accion=$_GET['action'];
	
	if ( $accion == "addUser"){
		
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		$pass2=$_POST['passver'];
		$limit=$_POST['espMax'];
		$dlimit=$_POST['espDir'];
		
		if(validarInput($user, "string")){
			$inputErrors['user']="inputCorrect";
		}else{
			$inputErrors['user']="inputError";
		}
		if(validarInput($dlimit, "numerico")){
			$inputErrors['espDir']="inputCorrect";
		}else{
			$inputErrors['espDir']="inputError";
		}
		if(validarInput($limit, "numerico")){
			$inputErrors['espMax']="inputCorrect";
		}else{
			$inputErrors['espMax']="inputError";
		}
		
		if ( validarInput($user, "string") && validarInput($dlimit, "numerico") && validarInput($limit, "numerico")) {
			$errors= newUser($user,$pass,$limit,$dlimit,$adminLink);
			if ($pass==$pass2 ){
				if ($errors == ""){
					$user="";
					$pass="";
					$pass2="";
					$limit="";
					$dlimit="";
					
					$inputErrors['user'] = "";
					$inputErrors['espDir'] = "";
					$inputErrors['espMax'] = "";
					
				}
			}else{
				$inputErrors['pass'] = "inputError";
				$errors= "Error: La contrasenya no es identica.";
			}
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
$query="SELECT * FROM user";
$result=mysql_query($query,$adminLink);
$rows=mysql_num_rows($result);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Pagina del Administrador de SACSEX</title>

<script type="text/javascript">

	function validarPass(){
		if ($("#pass1").val() != "" && $("#pass2").val() != ""){
			$("#pass1").val($.md5($("#pass1").val()));
			$("#pass2").val($.md5($("#pass2").val()));
			$("#newuser").submit();
		}else{
			alert("Password vacio!!");
		}
	}
</script>

</head>
<body>
<div class="body">
<div class="mainContainer">
<div class='addUserFormContainer'>
<form action='admin.php?action=addUser' id='newuser' method='post'>
<table>
	<tr>
		<td colspan="2"><b>Alta de usuario</b></td>
	</tr>
	<tr>
		<td>Usuario</td>
		<td><input class='<?php echo $inputErrors["user"];?>' type='text' name='user' value="<?php echo $user; ?>" /></td>

	</tr>
	<tr>
		<td>Contraseña: </td>
		<td><input class='<?php echo $inputErrors["pass"];?>'  type='password' id='pass1' name='pass' /></td>
	</tr>
	<tr>
		<td>Repite Contraseña: </td>
		<td><input  class='<?php echo $inputErrors["pass"];?>'  type='password' id='pass2' name='passver' /></td>
	</tr>
	<tr>
		<td>Tam Max en KB:</td>
		<td><input class='<?php echo $inputErrors["espDir"];?>'  type='text' name='espDir' value="<?php echo $dlimit; ?>" /></td>

	</tr>
	<tr>
		<td>Quota esp en KB:</td>
		<td><input class='<?php echo $inputErrors["espMax"];?>'  type='text' name='espMax' value="<?php echo $limit; ?>" /></td>
	
	</tr>
	
</table>

<input class="botonForm" type='button' value='Dar de alta' onclick='validarPass()' /></form>
</div>
<div class="userListContainer">
<div style="padding-bottom: 10px;"><b>Listado de usuarios</b></div>
<?php 
if ($rows >0){
	echo "<table><tr><th>Inst</th><th>ID</th><th>Usuario</th><th>Max por dia</th><th>Max total</th><th>Eliminar</th></tr>";
	while ($row=mysql_fetch_array($result)){
		echo "<tr>";
			if ($row["INSTALAT"] == "1"){
			echo "<td><img src='img/inst.png'/> </td>";
		}else{
			echo "<td></td>";
		}
		echo "<td>" . $row["ID"] . "</td>".
		"<td>" . $row["NAME"] ."</td>".
		"<td>" . $row["DAY_LIMIT"] . "</td>".
		"<td>" . $row["LIMIT"] ."</td>";		
		if (!esAdmin($row["ID"], $adminLink)){
			echo "<td><a href='admin.php?action=delUser&id=".$row["ID"]."' ><img src='img/DeleteIcon.png' /></a></td>";
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
