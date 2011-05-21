<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	
	$hoy=getdate();
	$pYear=$hoy['year'];
	$pMonth=$hoy['mon'];
	$pDay=$hoy['mday'];
	
	if(isset($_GET['accion'])){
		$dia=$_POST['dia'];
		$mes=$_POST['mes'];
		$anyo=$_POST['anyo'];	
		
		if (isset($_POST['fecha'])){
			if (checkdate($mes,$dia,$anyo)){
				$anDesp=$_POST['fecha'];
				$fecha=$anyo.'-'.$mes.'-'.$dia;
				$fok=true;
			}else{
				$fecha="";
				$fok=false;
			}
		}else{
			$fok=false;
		}
		
	}
	//Establezco el enlace con el que trabajara las busquedas
	$id=$_SESSION['id'];
	if(isset($_POST['fname'])&&$_POST['fname']!=''){
		$fname=$_POST['fname'];
		$nameQ=' AND FILENAME==$fname';
	}else{
		$nameQ='';
	}
	if($fok){
		if($anDesp=='+'){
			$dateQ=" AND (UNIX_TIMESTAMP(DATE)-UNIX_TIMESTAMP($fecha))<0";
		}elseif($anDesp=='-'){
			$dateQ=" AND (UNIX_TIMESTAMP(DATE)-UNIX_TIMESTAMP($fecha))>0";
		}else{
			$dateQ=" AND (UNIX_TIMESTAMP(DATE)=UNIX_TIMESTAMP($fecha))";
		}
	}else{
		$dateQ='';
	}
	$query="SELECT * FROM backups WHERE USER_ID=$id ".$nameQ.$dateQ;
	
	$bLink=conectar('bdsintesi');
	$result=mysql_query($query,$bLink);
	$rows=mysql_num_rows($result);
?>

<html>
<body>
<form action='asistenteBusqueda.php?accion=buscar' method=post>
	<table>
	<tr>
		<th>Fichero</th>
		<th>Periodo</th>
		<td></td>
	</tr>
	<tr>
		<td><input type='text' name='fname' /></td>
		<td>
			<SELECT name="fecha">
				<OPTION SELECTED>  </OPTION>
				<OPTION value='-'>antes</OPTION>
				<OPTION value='+'>despues</OPTION>
			</SELECT>
			<select name='dia'>
				<option value=''></option>
			<?php 
				for ($i=1;$i<=31;$i++){
					if ($i==$pDay){
						echo "<option value='$i' selected>$i</option>";
					}else{
						echo "<option value='$i'>$i</option>";
					}
				}
			?>
			</select>
			<select name='mes'>
				<option value=''></option>
				<?php 
					for ($i=1;$i<=12;$i++){
						if ($i==$pMonth){
							echo "<option value='$i' selected>$i</option>";
						}else{
							echo "<option value='$i'>$i</option>";
						}
					}
				?>
			</select>
			<select name='anyo'>
				<option value=''></option>
				<?php 
					for ($i=1970;$i<=$pYear;$i++){
						if ($i==$pYear){
							echo "<option value='$i' selected>$i</option>";
						}else{
							echo "<option value='$i'>$i</option>";
						}
					}
				?>
			</select>
		</td>
		<td>
			<input type='submit' value='buscar' />
		</td>
	</tr>
	</table>
	
</form>



<?php 
	if($rows>0){
		echo "<table><tr><th>ID</th><th>Fichero</th><th>Tamaño</th><th>Fecha</th></tr>";
		while($row=mysql_fetch_array($result)){
			echo "<tr>";
				echo "<td>".$row['ID']."</td>";
				echo "<td>".$row['FILENAME']."</td>";
				echo "<td>".$row['SIZE']."</td>";
				echo "<td>".$row['DATE']."</td>";
			echo "</tr>";			
		}
		echo"</table>";
	}	
	desconectar($bLink);
?>
</body>
</html>