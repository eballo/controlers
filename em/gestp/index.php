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
if(isset($_GET['pdirecto'])){
	$id = $_GET['pdirecto'];
	
	if(Comprobar_BBDD()==0){
	
		$con = Conectar();
		
		$res = Lanzar_Consulta("SELECT ID_Fab , Tipo from producto WHERE ID_Produc = $id ",$con);
		$dato = mysql_fetch_array($res[0]);
		
		
		$idtipo = $dato[1];
		$modo= "dataproduct";
		$idfab= $dato[0];
		
		$res = Lanzar_Consulta("SELECT ID_TipoP_S from tipo_producto WHERE ID_Tipo = $idtipo ",$con);
		$dato = mysql_fetch_array($res[0]);
		$idtipopadre = $dato[0];

	}
}else{
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
				}else{
						echo "Catergoria de Productos:<br><br>";
						$return=Gest_Arb_Tipo($idtipopadre);
						if ($return[0]!=1) {
								$ruta=Concat_Ruta($ruta,$return[1]);
						}else{
								Datos_Tipo($idtipo,0);
						}
				}
		echo "</div>
		<div>
		";
		if(isset($_GET['pdirecto'])){
			echo "<iframe name='centro'  src ='frame.php?modo=$modo&idtipo=$idtipo&idfab=$idfab&idproduct=$id' class='frame' frameborder=0 scrolling=no ></iframe>";
		}else{
			echo "<iframe name='centro'  src ='frame.php' class='frame' frameborder=0 scrolling=no ></iframe>";
		}
			
		
			echo "</div>
		
		";
		//Datos_Empresa();
}else{
	echo "<br><br><br><b>La conexi�n con la BBDD no se ha podido establecer. Error 102 </b>";
}
?>
