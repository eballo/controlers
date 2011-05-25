<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	include_once 'includes/cabecera.php';
	
	$link=conectar('bdsintesi');
//	if (isset($_POST['estilo'])){
//		$stile=$_POST['estilo'];
//	}else{
//		$stile='none';
//	}
	
	if (isset($_GET['error'])){
		$error = $_GET['error'];
	} else{
		$error = "";
	}
	
	$id=$_SESSION['id'];
	$hoy=getdate();
	$dateQ='';
	if (isset($_GET['accion'])){
		$accion = $_GET['accion'];
		if ($accion == "subir"){
			$filepath = $_POST['filepath'];
			if ($filepath != ""){
				$insQuery="INSERT INTO filepath (FILEPATH,USER_ID) VALUES ('$filepath',$id)";
				$res=mysql_query($insQuery,$link);
				if (!$res){
					echo "<script type='text/javascript'>
						alert('Error No se ha podido realizar la insercion');
					</script>";
				}
			} else {
				echo "<script type='text/javascript'>
					document.location = 'search.php?error=1';
				</script>";
			}
		}
		elseif($accion == "borrar"){
			$idFile = $_GET['idFile'];	
			$delQuery = "DELETE FROM filepath WHERE IDF=$idFile";
			$res = mysql_query($delQuery,$link);
			if (!$res){
				echo "<script type='text/javascript'>
					alert('No se pudo eliminar el directorio indicado');
				</script>";
				/*$error="No se pudo eliminar el directorio indicado";*/
			}
		}
		elseif($accion == "buscar"){
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
	}
	
	$query="SELECT * FROM backups WHERE USER_ID=$id".$nameQ.$dateQ.";";
	//Establezco el enlace con el que trabajara las busquedas y lanzo la consulta

	$bResult=mysql_query($query,$link);
	$brows=-1;
	if($bResult){
		$brows=mysql_num_rows($bResult);
	}

/* Borrar */
	$query="SELECT USER_ID,FILEPATH,IDF FROM filepath WHERE USER_ID=$id";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
?>
	

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Usuarios</title>
</head>
<body>
<div class='searchHead'>
	<div class='searchButton' onclick="configuracion()"> Configuracion</div>
	<div class='searchButtonOff' onclick="busqueda()">Busqueda</div>
</div>
<div id='searchFiles' class='searchFiles' style="display:none;">
	
	<div class='altafiles'>
	<h3>Alta de archivos:</h3>
	<form action='search.php?accion=subir' method='POST'>
		<div id='divErrors' style='color: red'>		
			<?php
			if ($error){
				switch($error){
					case 1:
						/* Error al validar un archivo nulo */
						echo "Directorio nulo no valido.";break;
						
					/*echo "<script type='text/javascript'>
						alert('$error');
					</script>";*/
				}
			}
			?>
		</div>
		File: <input type="text" name='filepath' size="40"/>
		<input type="submit" value='valida' /><br />
	</form>
	</div>
	<div class='borraFile'>

	<?php if ($rows >0){
		echo "<h3> Directorios: </h3>";
		echo "<table><tr><th colspan='2'>Rutas Del Usuario:</th></tr>";
		while($row=mysql_fetch_array($result)){
			printf("<tr><td>%s</td><td><a href=\"search.php?accion=borrar&idFile=%d\">Eliminar</a></td></tr>",$row['FILEPATH'],$row['IDF']);
		}
		echo "</table><br>";
	}
	?>
	</div>
</div>

<div id="buscaForm" class="buscaForm" style="display:none;">
<div class="valoresBusc">
<form action='search.php?accion=buscar' method=post>
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
</div>
<div class="tablaRes">
<?php 
	if($brows>0){
		echo "<table><tr><th>Fichero</th><th class='inTable'>Tamaño</th><th class='inTable'>Fecha</th></tr>";
		while($row=mysql_fetch_array($bResult)){
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
?>
</div>
</div>

</body>
</html>
<?php desconectar($link); ?>