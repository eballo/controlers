<?php
include_once 'includes/headers.php';
include_once 'includes/adminAuthValidation.php';
include_once 'includes/libsheader.php';

//Conexion base de datos
$adminLink=conectar($GLOBALS['MYSQL_BDNAME']);

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
			if ($pass==$pass2 ){
				$errors= newUser($user,$pass,megastokas($limit),megastokas($dlimit),$adminLink);
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
				$errors= "Error: La contrase&ntilde;a no es id&eacute;ntica.";
			}
		}
	}elseif ($accion == "delUser"){
		
	   $id=$_GET['id']; 
	   $errors=bajaUser($id, $adminLink);
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
<title>P&aacute;gina del Administrador de SACSEX</title>
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
<?php include_once 'includes/cabeceraAdmin.php';?>
<div class="body">
	<div class="mainContainer">
		<div class='addUserFormContainer'>
			<form action='admin.php?action=addUser' id='newuser' method='post' onkeypress="validarKeyEnter( event )">
			<table>
				<tr>
					<td colspan="2"><b>Alta de usuario</b></td>
				</tr>
				<tr>
					<td>Usuario</td>
					<td><input class='<?php echo $inputErrors["user"];?>' type='text' name='user' value="<?php echo $user; ?>" /></td>
			
				</tr>
				<tr>
					<td>Contrase&ntilde;a: </td>
					<td><input class='<?php echo $inputErrors["pass"];?>'  type='password' id='pass1' name='pass' /></td>
				</tr>
				<tr>
					<td>Repite Contrase&ntilde;a: </td>
					<td><input  class='<?php echo $inputErrors["pass"];?>'  type='password' id='pass2' name='passver' /></td>
				</tr>
				<tr>
					<td>Tam Max de subida en MB:</td>
					<td><input class='<?php echo $inputErrors["espDir"];?>'  type='text' name='espDir' value="<?php echo $dlimit; ?>" /></td>
			
				</tr>
				<tr>
					<td>Esp Total en MB:</td>
					<td><input class='<?php echo $inputErrors["espMax"];?>'  type='text' name='espMax' value="<?php echo $limit; ?>" /></td>
				</tr>
				
		</table>
			<input class="botonForm" type='button' value='Dar de alta' onclick='validarPass()' /></form>
		</div>
		<div class="userListContainer">
			<div style="padding-bottom: 10px;"><b>Listado de usuarios</b></div>
			<?php 
				if ($rows >0){
					echo "<table><tr><th>Inst</th><th>ID</th><th>Usuario</th><th colspan=2>Max por Subida</th><th colspan=2>Espacio Max Total</th><th>Acci&oacute;n</th></tr>";
					while ($row=mysql_fetch_array($result)){
						echo "<tr id='data".$row["ID"]."'>";
							if ($row["INSTALAT"] == "1"){
							echo "<td><img src='img/inst.png' title='Instalado' /> </td>";
						}else{
							echo "<td></td>";
						}
						echo "<td align='left'>" . $row["ID"] . "</td>".
						"<td align='left'>" . $row["NAME"] ."</td>".
						"<td><input class='inputEditable' style='text-align: right' name='dayLimit' type='text' value='".$row["DAY_LIMIT"]."' onkeypress=\"marcarChange('".$row["ID"]."')\"></td><td>KB</td>
						<td><input class='inputEditable' style='text-align: right' name='limit' type='text' value='".$row["MAX_LIMIT"]."' onkeypress=\"marcarChange('".$row["ID"]."')\"></td><td>KB</td>";
						if (!esAdmin($row["ID"], $adminLink)){
							echo "<td align='center'>
									<img id='imgSave' src='img/save_icon.png' style='display:none' onclick=\"save('".$row["ID"]."')\"/>
									<img src='img/DeleteIcon.png' onclick=\"javascript: document.location='admin.php?action=delUser&id=".$row["ID"]."'\"/>
								</td>";
						}else{
							echo "<td>Bloqueado</td>";
						}
						echo "</tr>";
						}
					echo "</table>";
				}else{
					echo "No hay usuarios registrados <br />";
				}
			?>
		</div>
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
