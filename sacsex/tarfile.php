<?php
	require_once 'Archive/Tar.php';
	
	function readTar($nombre){
		$obj = new Archive_Tar($nombre); // name of archive
		
		$files = $obj->listContent();       // array of file information
		
		echo '<table>';
		echo '<tr><th>Nom</th><th>Data</th><th>tamany</th><th>Descarga</th></tr>';		
		foreach ($files as $f) {
			if ($f['size']>0){
			echo "<tr>";			
			echo "<form method=POST action=tarfile.php?accion=d>";
			echo "<input type='hidden' name='file' value='".$f['filename']."' />";
				echo "<td>".$f['filename']."</td>";
				echo "<td>".$f['size']."</td>";
				echo "<td>".date("d-m-Y", $f['mtime'])."</td>";
				echo "<td><input type=submit value=descarga /></td>";
				echo"</form>";
			echo "</tr>";	
			}	    
		}echo "</table>";
	}
	
	function extraxtOneTar($origen,$dest,$nomArxiu){
//extraer 1 o varios
		if(file_exists($origen)){
		    $obj = new Archive_Tar($origen); // name of TAR file
		}else {
		    die('File does not exist');
		}
		$files= array($nomArxiu);   // files to extract
		if(!$obj->extractList($files,$dest)) {
		    $error="Error al extraer el archivo";
		}
		
	}
	
	readTar('sacsex.tar.gz');
	if (isset($_GET['accion'])&&$_GET['accion']=='d'){
		
		$nomArxiu=$_POST['file'];
		$ruta='tmp';//home/sacs/bkps/'.$id.'
		mkdir($ruta);
		echo $nomArxiu;
		extraxtOneTar('sacsex.tar.gz',$ruta,$nomArxiu);//extraigo el archivo a la ruta temporal creada
		$nuevaRuta=$ruta."/".$nomArxiu; //genero la nueva ruta del archivo
		$arxiu=explode("/",$nuevaRuta);//Recojo el nombre del archivo (al descomprimir, sale con ruta entera)
		$longitud=count($arxiu);//Guardo la longitud total de los elementos del array y me situo en la posicion long-1 (la ultima)
		$long=$longitud-1;
		$ruta=$ruta.'/'.$arxiu[$long];
		
		//$nuevaRuta=str_replace(" ","\ ",$nuevaRuta); //En caso que el directorio o nombre contenga espacios los escapo
		$comando='mv "'.$nuevaRuta.'" '.$ruta;
		exec($comando,$res);
		echo "<br>$comando";
		echo "<script type='text/javascript'>
			document.location='descarga.php?file=$ruta';
		</script>";		
	}
?>
