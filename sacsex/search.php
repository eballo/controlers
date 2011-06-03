<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	
	$nameQ="";
	
	//variables para el control de los botones
	$configB="searchButtonClick";
	$searchB="searchButtonOff";
	
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
	$bnom='';
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
		/* Accion buscar */
		elseif($accion == "buscar"){
			$searchStile='display:block';
			$insertStile='display:none';
			
			$configB="searchButton";
			$searchB="searchButtonOffClick";
			
			if (isset($_GET['elimBkp']) && $_GET['elimBkp']){
				$idBkp = $_GET['idBkp'];
				eliminaBackup($idBkp,$id,$link);				
			} else{
				$elimBkp = false;
			}
			
			
			if (isset($_POST['fname']) && $_POST['fname']!=''){
				$bnom=true;
				$nom=$_POST['fname'];
			}else{
				$bnom=false;
			}
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
					$dateQ=" AND TIMESTAMPDIFF($text,DATE,curdate()) >= $dias";
				}
			}else{
				$dateQ='';
			}
		}
	}
/* Query para accion Buscar ficheros tar */
	$query="SELECT * FROM backups WHERE USER_ID=$id".$dateQ.";";
	$bResult=mysql_query($query,$link);
	$brows=-1;
	if($bResult){
		$brows=mysql_num_rows($bResult);
	}

/* Query para accion Borrar */
	$query="SELECT * FROM filepath WHERE USER_ID=$id";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	
/* Query para la cabecera del usuario */
	$queryUser="SELECT * FROM user WHERE ID=$id";
	$resultUser=mysql_query($queryUser,$link);
	$array=mysql_fetch_array($resultUser);
	$usuario=$array['NAME'];
	$sizeTotal=$array['MAX_LIMIT'];
	/* Query para la suma total de tamaño de ficheros tar del usuario */
	$tamOcup="SELECT sum(size)as 'total' FROM backups GROUP BY USER_ID HAVING USER_ID=$id";
	$resultTam=mysql_query($tamOcup,$link);
	$sizeA=mysql_fetch_array($resultTam);
	$sizeOcup=$sizeA[0];

	$sizeRes=$sizeTotal - $sizeOcup;
	
	$limitWarn = ( $sizeTotal * 75 ) / 100;
	$limitError = ( $sizeTotal * 90 ) / 100; 
	
	$styleHeader ="headerMain";
	$msgError = "";
	
	if ( $sizeOcup >= $limitError ){
		$styleHeader="headerMainLimitError";
		$msgError="Superado el 90% del espacio total";
	}else{
		if ( $sizeOcup >= $limitWarn ){
				$styleHeader="headerMainLimitWarn";
				$msgError="Superado el 75% del espacio total";
		}
	}
	
	if ($sizeRes <= 0){
		$sizeRes = "0";
	} 
	if ($sizeOcup == 0){
		$sizeOcup = "0";
	}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Usuarios</title>
</head>
<body>
<?php  include_once 'includes/cabecera.php'; ?>
<div class="body">
	<div class="mainContainerSearch">
		<div class='searchHead'>
			<div id='configB' class='<?php echo "$configB"; ?>' onclick="configuracion()"> Configuracion</div>
			<div id='searchB' class='<?php echo "$searchB"; ?>' onclick="busqueda()"> Busqueda</div>
		</div>
		
		<div id='searchFiles' class='searchFiles' style="<?php echo $insertStile ?>;">
			
			<div class='altafiles'>
			<h3>Alta de directorios:</h3>
			<form action='search.php?accion=subir' method='POST'>
				<div id='divErrors' style='color: red'>		
					<?php
					if ($error == 1){
						/* Error al validar un archivo nulo */
						echo "Directorio nulo no valido.";
						/*echo "<script type='text/javascript'>
							alert('$error');
						</script>";*/
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
						<tr>
						<th></th>
						<th class='inTable'>Ruta</th>
						<th class='inTable'>Eliminar</th>
						</tr>";
					while($row=mysql_fetch_array($result)){
						echo "<tr>
							<td style='width:20px'>";
								if ($row['VALID'] != 0){
									echo "<img src='img/warn.png' title='ruta no valida' width='20px' height='23px' style='margin-bottom: 2px' /> ";								
								}
							echo "</td>
							<td class='inTable'>".$row['FILEPATH']."</td>
							<td class='inTable'><img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=borrar&idFile=".$row['IDF']."'\" /></td>".
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
		<div class="searchFormContainer">
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
						$enc=false;
						if ( $brows > 0 ){
							echo "<tr><th>Down</th><th>Nom</th><th>Tamany</th><th>Fecha</th><th>Accion</th></tr>"; 								
							while($row=mysql_fetch_array($bResult)){
								//echo "<tr><td>".$row['ID']. "</td></tr>";
								$idf=$row['ID'];  //id del archivo en la BD
								$filename=$row['FILENAME'];	 //tar que estamos mirando
								
								if ( $bnom ){
									$dir=$GLOBALS['BKPS_PATH']."/".$id;
									$rutabkp="$dir/$filename";
									// Funcion readTar para obtener contenido de tar backup
									$tarA=readTar($rutabkp);											
									foreach ($tarA as $subelem){
										//if ($subelem['size']>0){
											$nomfitxer=basename($subelem['filename']);
											if ($nomfitxer == $nom){	// $nom -> nombre de fichero a buscar
												if(isset($dias) && $dias!=''){
													echo "<tr>
														  <td><img src='img/save_icon.png' onclick=\"descargarFichero('".$row['ID']."')\"></td> 
													      <td>".$row['FILENAME']. "</td>
														  <td>".$row['SIZE']. " KB </td>
														  <td>".$row['DATE']. "</td>";
													$enc=true;
												} else{
													echo "
														 <tr><td><img src='img/save_icon.png' onclick=\"descargarFichero('".$row['ID']."')\"></td>
														 <td>".$subelem['filename']."</td>
														  <td>".$subelem['size']." KB</td>
														  <td>".date("d-m-Y", $subelem['mtime'])."</td>";
													$enc=true;
												}
												echo "<td>
												<img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=buscar&elimBkp=true&idBkp=".$idf." '\"/>
												</td>";
												echo "</tr>";
											}
										//}
									}								
								}else{
									echo "<tr><td><img src='img/save_icon.png' onclick=\"descargarFichero('".$row['ID']."')\"></td>
										<td>".$row['FILENAME']. "</td>
										<td>".$row['SIZE']. " KB </td>
										<td>".$row['DATE']. "</td>";
									echo "<td>
									<img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=buscar&elimBkp=true&idBkp=".$idf." '\"/>
									</td>";
									echo "</tr>";
									$enc=true;
								}
							}
						}
						if (! $enc){
							echo "<tr><td>No se han encontrado resultados para los parametros facilitados</td></tr>";
						}
					echo "</table>";

				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php desconectar($link); ?>
</body>
</html>