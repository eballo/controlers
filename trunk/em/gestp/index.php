<link rel='stylesheet' type='text/css' href='css/main.css'></link>
 <?php 
//session_start();
/**
 * GestProduct 2008 Beta
 * job3_14@author 
 * job3@copyright 2008
 */

include "includes/funciones.php";

//////////////////////////
if ( isset($_GET['idtipo'])){
	$idtipo=$_GET['idtipo'];
}else{
	$idtipo="ini";
}
if ( isset($_GET['modo'])){
	$modo=$_GET['modo'];
}else{
	$modo="ini";
}
if ( isset($_GET['idfab'])){
	$idfab=$_GET['idfab'];
}else{
	$idfab="null";
}
if ( isset($_GET['idproduct'])){
	$idproduct=$_GET['idproduct'];
}else{
	$idproduct="null";
}
if ( !isset ($_SESSION['ruta'])){
	$ruta[0]="/emsa";
	$_SESSION['ruta']=$ruta;
}else{
	$ruta=$_SESSION['ruta'];
}

///////////////////////////
//Codigo de la web
//COdigo del programa

if(Comprobar_BBDD()==0){
		echo "<div class='ruta'>";
				Mostrar_Ruta($idtipo);
		echo "</div>
		<div class='rutainfo'>[Clic en los nombres de la ruta para desplazarse]</div>
							
		<div class='categoriaProductos'>";
				if ($modo=='ini'){
					echo "Catergoria de Productos:<br><br>";
						$return=Gest_Arb_Tipo($idtipo);
						if ($return[0]!=1) {
								$ruta=Concat_Ruta($ruta,$return[1]);
						}else{
								Datos_Tipo($idtipo,0);
						}	
				}
		echo "</div>
		<div>
			<iframe name='centro'  src ='frame.php' class='frame'></iframe>
		</div>
		
		";
		//Datos_Empresa();
}else{
	echo "<br><br><br><b>La conexi�n con la BBDD no se ha podido establecer. Error 102 </b>";
}
?>
