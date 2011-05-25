<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	include_once 'includes/cabecera.php';
	
	$id=$_SESSION['id'];
	//Construyo la parte de query temporal
	$hoy=getdate();
	$dateQ='';
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
<div class='searchHead'>
	<div class='searchButton' onclick="muestraForm()"> Muestra </div>
	<div class='searchButtonOff' onclick="ocultaForm()"> Oculta </div>
</div>
<div id="buscaForm" class="buscaForm" style="display:none;">
<div class="valoresBusc">
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
					<option value='anyos'>aÃ±os</option>	
				</optgroup>		
			</select>
		</td>
		<td>
			<input type='submit' value='buscar' />
		</td>
	</tr>
	</table>
	
</form>
</div>
<div class="tablaRes">
<?php 
	if($rows>0){
		echo "<table><tr><th>Fichero</th><th class='inTable'>Tamaño</th><th class='inTable'>Fecha</th></tr>";
		while($row=mysql_fetch_array($result)){
			echo "<tr>";
				echo "<td>".$row['FILENAME']."</td>";
				echo "<td class='inTable'>".$row['SIZE']."</td>";
				echo "<td class='inTable'>".$row['DATE']."</td>";
			echo "</tr>";			
		}
		echo"</table>";
	}else{
		echo "No se han encontrado resultados para los parametros facilitados";
	}	
	desconectar($bLink);
?>
</div>
</div>
</body>
</html>