<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	include_once 'includes/cabecera.php';
	
	$link=conectar('bdsintesi');
	if (isset($_GET['error'])){
		$error = $_GET['error'];
	} else{
		$error = "";
	}
	$id=$_SESSION['id'];
	$hoy=getdate();
	$dateQ='';
	$searchStile='display:none';
	//$insertStile='display:none';
	$insertStile='display:block';
	
	if (isset($_GET['accion'])){
		$accion = $_GET['accion'];
		if ($accion == "subir"){
			$insertStile='display:block';
			$searchStile='display:none';
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
				/*$insertStile='display:block';
				$searchStile='display:none';*/
				echo "<script type='text/javascript'>
					document.location = 'search.php?error=1';
				</script>";
			}
		}
		elseif($accion == "borrar"){			
			$insertStile='display:block';
			$searchStile='display:none';
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
			$searchStile='display:block';
			$insertStile='display:none';
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
	$query="SELECT * FROM filepath WHERE USER_ID=$id";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
?>
	

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Usuarios</title>
</head>
<body>

<div class='infoUser'>
<?php 

	$queryUser="SELECT * FROM user WHERE ID=$id";
	$resultUser=mysql_query($queryUser,$link);
	$array=mysql_fetch_array($resultUser);
	$usuario=$array['NAME'];
	$sizeTotal=$array['LIMIT'];
 
	$tamOcup="SELECT sum(size)as 'total' FROM backups GROUP BY USER_ID HAVING USER_ID=$id";
	$resultTam=mysql_query($tamOcup,$link);
	$sizeA=mysql_fetch_array($resultTam);
	$sizeOcup=$sizeA[0];
	
	$sizeOcup=(string)$sizeOcup;
	
	$sizeRes=$sizeTotal - $sizeOcup;
/*
	if ($sizeOcup == 0){
		$sizeOcup = 0;
	echo "$sizeOcup";
		echo "HOLA";
	}
	if ($sizeRes){
		echo $sizeRes;
	}
	MOSTRAR CERO
	*/
	echo "<h3>Información:</h3>";
	echo "<table>		
		<tr><td>Usuario: ".$usuario."</td></tr>
		<tr><td>Espacio ocupado: ".$sizeOcup." MB</td></tr>
		<tr><td>Espacio restante: ".$sizeRes." MB</td></tr>
	</table>";
?>
</div>

<div class='searchHead'>
	<div class='searchButton' onclick="configuracion()"> Configuracion</div>
	<div class='searchButtonOff' onclick="busqueda()"> Busqueda</div>
</div>

<div id='searchFiles' class='searchFiles' style="<?php echo $insertStile ?>;">
	
	<div class='altafiles'>
	<h3>Alta de directorios:</h3>
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
	<div class="tablaRes">
	<?php
	echo "<h3> Rutas del usuario: </h3>";
	if ($rows >0){
		echo "<table>		
			<tr><th class='inTable'>Ruta</th>
			<th class='inTable'>Valido</th>
			<th class='inTable'>Eliminar</th>
			</tr>";
		while($row=mysql_fetch_array($result)){
			echo "<tr>".
			"<td class='inTable'>".$row['FILEPATH']."</td>".
			"<td class='inTable'>".$row['VALID']."</td>".
			"<td class='inTable'><img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=borrar&idFile=".$row['IDF']."'\" /></td>".
			"</tr>";
		}
	}
	else{
		echo "<td>Ninguna ruta establecida</td><br>";
	}
	echo "</table><br>";
	?>
	</div>
	</div>
</div>

<div id="buscaForm" class="buscaForm" style="<?php echo $searchStile ?>;">
	<div class="valoresBusc">
	<form action='search.php?accion=buscar' method=post>
		<table>
		<tr>
			<th>Fichero</th>
			<th>Periodo</th>
			<td></td>
		</tr>
		<tr>
			<td><input type='text' name='fname' size='40'/></td>
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
		echo "<table>";
		if($brows>0){
			echo "<tr><th>Fichero</th><th class='inTable'>Tamaño</th><th class='inTable'>Fecha</th></tr>";
			while($row=mysql_fetch_array($bResult)){
				echo "<tr>";
					echo "<td class='inTable'>".$row['FILENAME']."</td>";
					echo "<td class='inTable'>".$row['SIZE']."</td>";
					echo "<td class='inTable'>".$row['DATE']."</td>";
				echo "</tr>";			
			}
		}else{
			//echo "No se han encontrado resultados para los parametros facilitados";
			echo "No se han encontrado resultados";
		}
		echo"</table>";
	?>
	</div>
</div>
<?php desconectar($link); ?>
</body>
</html>
