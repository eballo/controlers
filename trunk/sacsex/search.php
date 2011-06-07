<?php
	include_once 'includes/headers.php';
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/libsheader.php';
	
	$nameQ="";
	
	//variables para el control de los botones
	$configB="searchButtonClick";
	$searchB="searchButtonOff";
	$purgaB="purgaButton";
	
	$purgaError=false; //variable para controlar los errores del formulario purga
	
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	if (isset($_GET['error'])){
		$error = $_GET['error'];
	} else{
		$error = "";
	}
	$id=$_SESSION['id'];
	$hoy=getdate();
	$dateQ='';
	$searchStile='display:none';
	$insertStile='display:block';
	$purgaStile='display:none';
	
	$bnom='';
	if (isset($_GET['accion'])){
		$accion = $_GET['accion'];
		/* Accion subir */
		if ($accion == "subir"){
			
			$searchStile='display:none';
			$insertStile='display:block';
			$purgaStile='display:none';
	
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
		/* Accion borrar */
		elseif($accion == "borrar"){
			$searchStile='display:none';
			$insertStile='display:block';
			$purgaStile='display:none';
	
			$idFile = $_GET['idFile'];
			$delQuery = "DELETE FROM filepath WHERE IDF=$idFile AND USER_ID=$id";
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
			$purgaStile='display:none';
				
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
					$dateQ=" AND TIMESTAMPDIFF($text,TIMEDATE,curdate()) < $dias";
				}else{
					$dateQ=" AND TIMESTAMPDIFF($text,TIMEDATE,curdate()) >= $dias";
				}
			}else{
				$dateQ='';
			}
		}elseif ($accion=='upurga') {
			$searchStile='display:none';
			$insertStile='display:none';
			$purgaStile='display:block';
			
			$configB="searchButton";
			$searchB="searchButtonOff";
			$purgaB="purgaButtonClick";
			
			if (isset($_POST['freqPurga']) && isset($_POST['numPurga'])){
				if ( esNumero($_POST['freqPurga']) && esNumero($_POST['numPurga']) ){
					$updatePurga = "UPDATE purga SET VALOR=".$_POST['numPurga']." , FREQ=".$_POST['freqPurga']." WHERE USER_ID=$id";
					$resPurga = mysql_query($updatePurga,$link);
					if (!$resPurga){
						$valorPurgaError=$_POST['numPurga'];
						$purgaError=true;
					}
				}else{
					$valorPurgaError=$_POST['numPurga'];
					$purgaError=true;
				}
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

/* Query para cargar los datos de la purga */
	$query="SELECT * FROM purga WHERE USER_ID=$id";
	$dataPurga=mysql_query($query,$link);
	$purgaRows=-1;
	if($dataPurga){
		$purgaDataA=mysql_fetch_array($dataPurga);
		$modoPurga=$purgaDataA['FREQ'];
		$valorPurga=$purgaDataA['VALOR'];
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
			<div id='configB' class='<?php echo "$configB"; ?>' onclick="configuracion()"> Configuraci&oacute;n</div>
			<div id='searchB' class='<?php echo "$searchB"; ?>' onclick="busqueda()"> B&uacute;squeda</div>
			<div id='purgaB' class='<?php echo "$purgaB"; ?>' onclick="purga()"> Limpieza autom&aacute;tica</div>
		</div>
		
		<div id='searchFiles' class='searchFiles' style="<?php echo $insertStile ?>;">
			
			<div class='altafiles'>
			<h3>Alta de directorios:</h3>
			<form action='search.php?accion=subir' method='POST'>
				<div id='divErrors' style='color: red'>		
					<?php
					if ($error == 1){
						/* Error al validar un archivo nulo */
						echo "Directorio nulo no valido.";					}
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
								<optgroup label="relaci&oacute;n">
									<OPTION value='min'>Hace menos de</OPTION>
									<OPTION value='max'>Hace m&aacute;s de</OPTION>
								</optgroup>
							</SELECT>
							<input type=text name='numd' style="width:40px;" />
							<select name='freq'>
								<optgroup label="tiempo">
									<option value='dias'>d&iacute;as</option>
									<option value='meses'>meses</option>
									<option value='anyos'>a&ntilde;os</option>	
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
							$title=true; 								
							while($row=mysql_fetch_array($bResult)){
								$idf=$row['ID'];  //id del archivo en la BD
								$filename=$row['FILENAME'];	 //tar que estamos mirando
								
								if ( $bnom ){
									$dir=$GLOBALS['BKPS_PATH']."/".$id;
									$rutabkp="$dir/$filename";
									// Funcion readTar para obtener contenido de tar backup
									$tarA=readTar($rutabkp);										
									foreach ($tarA as $subelem){
										$nomfitxer=basename($subelem['filename']);
										if ($nomfitxer == $nom){	// $nom -> nombre de fichero a buscar
											if(isset($dias) && $dias!=''){
												if ($title){
													echo "<tr><th>Down</th><th>Backup</th><th>Tama&ntilde;o</th><th>Fecha</th><th align=center>Acci&oacute;n</th></tr>";
												}
												echo "<tr>";	
												echo "<tr><td><img src='img/Download-icon-32.png' title='Descarga este tar' onclick=\"descargarFichero('".$row['ID']."')\"></td>
													<td>".$row['FILENAME']. "</td>
												  	<td>".$row['SIZE']. " KB </td>
													<td>".fechaEsp($row['TIMEDATE']). "</td>";
												$enc=true;
											}else{
												if($title){
													echo "<tr><th>Down</th><th>Fichero</th><th>Backup</th><th>Tama&ntilde;o</th><th>Fecha</th><th align=center>Acci&oacute;n</th></tr>";
												}
												echo "<tr>";
												echo "<tr><td><img src='img/Download-icon-32.png' title='Descarga este tar' onclick=\"descargarFichero('".$row['ID']."')\"></td>
													<td>".$subelem['filename']."</td>
												 	<td>".$row['FILENAME']. "</td>
											     	<td>".$subelem['size']." KB</td>
													<td>".date("d-m-Y H:i:s",$subelem['mtime'])."</td>";
												$enc=true;
											}
											echo "<td align=center>
												<img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=buscar&elimBkp=true&idBkp=".$idf." '\"/>
												</td>";
											echo "</tr>";
											$title=false;
										}
									}								
								}else{
									if ($title){
										echo "<tr><th>Down</th><th>Backup</th><th>Tama&ntilde;o</th><th>Fecha</th><th align=center>Acci&oacute;n</th></tr>";
									}
									echo "<tr><td><img src='img/Download-icon-32.png' title='Descarga este tar' onclick=\"descargarFichero('".$row['ID']."')\"></td>
										<td>".$row['FILENAME']. "</td>
										<td>".$row['SIZE']. " KB </td>
										<td>".fechaEsp($row['TIMEDATE']). "</td>";
									echo "<td align=center>
									<img src='img/DeleteIcon.png' onclick=\"javascript: document.location='search.php?accion=buscar&elimBkp=true&idBkp=".$idf." '\"/>
									</td>";
									echo "</tr>";
									$enc=true;
									$title=false;
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
		<div class="purgaContainer">
			<div id="purgaForm" class="purgaForm"  style="<?php echo $purgaStile; ?>;">
				<div class="<?php if ($purgaError){ echo "purgaInfoError"; }else{ echo "purgaInfo";} ?>"  >
				Configure la limpieza automatizada de sus backups seleccionando el n&uacute;mero de
				<b>d&iacute;as, meses o a&ntilde;os</b>  que desea guardar, 
				<b>la tarea de limpieza ser&aacute; ejecutada cuando el cliente sacsex lanze un nuevo backup.</b>
				</div>
				<div class="purgaFormContainer">
					<form action="search.php?accion=upurga" method="POST">
					<table>
						<tr>
							<td>
								<input <?php if ($purgaError){ echo "class='inputError'"; } ?> type="text" name="numPurga" size="4" value="<?php if(!$purgaError) { echo $valorPurga; }else{ echo $valorPurgaError; } ?>"></input>
							</td>
							<td>
								<select name="freqPurga"  >
									<option value="0"  <?php if ( $modoPurga == 0){ echo "selected='selected'"; }?>>D&iacute;a/s</option>
									<option value="1" <?php if ( $modoPurga == 1){ echo "selected='selected'"; }?>>Mes/es</option>
									<option value="2" <?php if ( $modoPurga == 2){ echo "selected='selected'"; }?>>A&ntilde;o/s</option>
								</select>
							</td>
						</tr>
					</table>
						<b> Para no eliminar backpus introduzca 0.</b>
						<div class="purgaSaveB">
							<input type='submit' value='Guardar' />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php desconectar($link); ?>
</body>
</html>