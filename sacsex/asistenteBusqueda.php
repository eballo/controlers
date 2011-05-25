<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	
	$id=$_SESSION['id'];
	//Construyo la parte de query temporal
	$hoy=getdate();
	if(isset($_GET['accion'])){		
		if (isset($_POST['numd']) && $_POST['numd']!=''){
			$dias=$_POST['numd'];
			switch ($_POST['freq']) {
				case 'dias':
					$text='DAY';
					break;
				case 'meses':
					$text='MONTH';
					break;
				case 'anyos':
					$text='YEAR';
				break;
			} 
			if ($_POST['rel']=='min'){
				$dateQ=" AND TIMESTAMPDIFF($text,DATE,curdate()) < $dias";
			}else{
				$dateQ=" AND TIMESTAMPDIFF($text,DATE,curdate()) > $dias";
			}
		}else{
			$dateQ='';
		}
	}
	//Construyo la parte de la query para el nombre de fichero a buscar
	if(isset($_POST['fname']) && $_POST['fname']!=''){
		$fname=$_POST['fname'];
		$nameQ=" AND FILENAME='$fname'";
	}else{
		$nameQ='';
	}
	$query="SELECT * FROM backups WHERE USER_ID=$id".$nameQ.$dateQ.";";
	//Establezco el enlace con el que trabajara las busquedas y lanzo la consulta
	$bLink=conectar('bdsintesi');
	$result=mysql_query($query,$bLink);
	$rows=-1;
	if($result){
		$rows=mysql_num_rows($result);
	}
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
			<SELECT name="rel">
				<optgroup label="relacion">
					<OPTION value='min'>Hace menos de</OPTION>
					<OPTION value='max'>Hace mas de</OPTION>
				</optgroup>
			</SELECT>
			<input type=text name='numd' style="width:40px;" />
			<select name='freq'>
				<optgroup label="tiempo">
					<option value='dias'>dias</option>
					<option value='meses'>meses</option>
					<option value='anyos'>años</option>	
				</optgroup>		
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
		echo "<table><tr><th>Fichero</th><th>Tamaño</th><th>Fecha</th></tr>";
		while($row=mysql_fetch_array($result)){
			echo "<tr>";
				echo "<td>".$row['FILENAME']."</td>";
				echo "<td>".$row['SIZE']."</td>";
				echo "<td>".$row['DATE']."</td>";
			echo "</tr>";			
		}
		echo"</table>";
	}else{
		echo "No se han encontrado resultados para los parametros facilitados";
	}	
	desconectar($bLink);
?>
</body>
</html>