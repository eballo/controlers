<?php

/**
 * Funciones GestProduct 2008
 * Job3_14@author 
 * job3@copyright 2008
 */

//Declaracion de Funciones

function Conectar(){
	$link=mysql_connect("mysql.telesofa.com","qav939","servidor");
	mysql_select_db("qav939",$link);
	return($link);
}
function Desconectar($link){
	mysql_close($link);
}
function Comprobar_Exclusi($ID_Tipo,$conex){

	$numerosal=Lanzar_Consulta("select Nombre FROM tipo_producto WHERE ID_TipoP_S=$ID_Tipo",$conex);
	$res=mysql_num_rows($numerosal[0]);

	return($res);
	//Comprueba si $ID_Tipo es un conjunto y devuelve un valor 0 si no es un conjunto diferente de cero si es un conjunto de tipos de producto
}
function Gest_Arb_Tipo($tipo){
	$conex=Conectar();
		if ($tipo=='ini'){
			$tipos=Lanzar_Consulta("select Nombre,ID_Tipo FROM tipo_producto WHERE ID_TipoP_S=0",$conex);	
			for ($i=0;$i<$tipos[1];$i++){
				$res=mysql_fetch_array($tipos[0]);
					$comp=Comprobar_Exclusi($res[1],$conex);
					if( $comp==0 ){
						echo "
						<a href='frame.php?idtipo=$res[1]&modo=dataidtipo' target='centro' ><img src='img/int.png' border='0'></a><font face='Arial' size=-2 ><b><a href='frame.php?idtipo=$res[1]&modo=fab' target='centro' ><img src='img/plus.png' border='0'> $res[0]</a></b></font><br>
						";
					}else{
						echo "
						<a href='frame.php?idtipo=$res[1]&modo=dataidtipo' target='centro' ><img src='img/int.png' border='0'></a><font face='Arial' size=-2 ><b><a href='index.php?idtipo=$res[1]'><img src='img/plus.png' border='0'> $res[0]</a></b></font><br>
						";
					}
			}
			$rc[0]=0;
			$rc[1]=$res[0];
		}else{
			$tipos=Lanzar_Consulta("select Nombre,ID_Tipo FROM tipo_producto WHERE ID_TipoP_S=$tipo",$conex);
			if ($tipos[1]>0){
				for ($i=0;$i<$tipos[1];$i++){
					$res=mysql_fetch_array($tipos[0]);
					$comp=Comprobar_Exclusi($res[1],$conex);
					if( $comp==0 ){
						echo "
						<a href='frame.php?idtipo=$res[1]&modo=dataidtipo' target='centro' ><img src='img/int.png' border='0'></a><font face='Arial' size=-2 ><b><a href='frame.php?idtipo=$res[1]&modo=fab' target='centro' ><img src='img/plus.png' border='0'> $res[0]</a></b></font><br>
						";
					}else{
						echo "
						<a href='frame.php?idtipo=$res[1]&modo=dataidtipo' target='centro' ><img src='img/int.png' border='0'></a><font face='Arial' size=-2 ><b><a href='index.php?idtipo=$res[1]'><img src='img/plus.png' border='0'> $res[0]</a></b>&nbsp;</font><br>
						";
					}
				}
				$rc[0]=0;
				$rc[1]=$res[0];
			}else{
				$rc[0]=1;
			}
		}
	Desconectar($conex);
	return($rc);
	//buscar en la bbdd las entradas para ese tipo de producto si no tiene dependencias mostrar los fabricantes que tienen algun producto con de ese tipo.
}
function Mostrar_Fabricantes_Tipo($ID_Tipo){
	$conex_fab=Conectar();
		$tipos=Lanzar_Consulta("SELECT distinct(fabricante.Nombre), fabricante.ID_Fab,fabricante.Img FROM fabricante,producto where fabricante.ID_Fab=producto.ID_Fab and producto.tipo=$ID_Tipo",$conex_fab);	
		if($tipos[1]!=0){
			for ($i=0;$i<$tipos[1];$i++){
				$res=mysql_fetch_array($tipos[0]);
				echo "
				<a href='frame.php?idtipo=$ID_Tipo&idfab=$res[1]&modo=produc'>
				<div class='logoFabricante'>
					<img style='position:absolute;z-index: 2' src='img/reflejofab.png'>
					<img  src='$res[2]' width=80px height=40px'/>
				</div>
				</a>
				";
			}
		}else{
			echo "<font face='Arial' size=-2 ><b>No existe ning�n fabricante de este tipo de producto</b></font>";
		}
	Desconectar($conex_fab);
	//Muestra los fabricantes que tienen ese tipo de productos
}
function Mostrar_Producto_Fabricante($ID_Fab,$ID_Tipo){
	$conex_fab_pro=Conectar();
		$productos=Lanzar_Consulta("SELECT producto.Nombre,producto.ID_Produc FROM fabricante,producto where fabricante.ID_Fab=producto.ID_Fab and producto.tipo=$ID_Tipo and fabricante.ID_Fab=$ID_Fab",$conex_fab_pro);	
		for ($i=0;$i<$productos[1];$i++){
			$res=mysql_fetch_array($productos[0]);
			echo "
			<font face='Arial' size=-2 ><b><a href='frame.php?idtipo=$ID_Tipo&idfab=$ID_Fab&idproduct=$res[1]&modo=dataproduct'><img src='img/plus.png' border='0'> $res[0]</a></b></font><br>
			";
		}
	Desconectar($conex_fab_pro);	
	//muestra todos los productos del tipo $ID_tipo con ese id frabricante
}
function Mostrar_Datos_Producto($ID_Produc,$modosalida){
	$conex_product=Conectar();
		$datosproducto=Lanzar_Consulta("SELECT * FROM producto where ID_Produc='$ID_Produc'",$conex_product);	
			$res=mysql_fetch_array($datosproducto[0]);
			if($modosalida==1){
				echo "$res[2]";
			}else{
				echo "
				<table bgcolor='#F5F5F5' border='0' WIDTH='100%' HEIGHT='100%'>
				<tr>
					<td HEIGHT='100%' valign='TOP'>
						<table>
							<tr>
								<td>
									<a href='$res[4]' target='_blank' >
									<img style='position:absolute;z-index: 2' src='img/reflejopro.png'>
									<img WIDTH='100' HEIGHT='100' src='$res[4]' border='1' bordercolor='#C0C0C0'>
									</a>
								</td>
							</tr>
						</table>
					</td>
					<td valign='TOP' style='font-size: x-small;'><b>$res[2]</b><br>$res[3]</td>
				</tr>
				<tr>
					<td colspan='2' ></td>
				</tr>
				</table>
				";	
			}
	Desconectar($conex_product);
}
function Concat_Ruta($vruta,$dir){
	$tamruta=count($vruta);
	
	if ($vruta[$tamruta-1]!=$dir){
		$vruta[$tamruta]='/';
		$vruta[$tamruta+1]=$dir;
	}
	return($vruta);
	//a�adir a la ruta
}
function Desconcat_Ruta($vruta){
	$tamruta=count($vruta);
	$vruta[$tamruta]='';
	$vruta[$tamruta-1]='';
	return($vruta);
	//eliminar de la ruta
}
function Mostrar_Ruta($idtipo){

	if($idtipo!="ini"){
		echo "<font face='Arial' size=-2>";
		$conex_tipo=Conectar();
		$datostipo=Lanzar_Consulta("select Nombre,ID_TipoP_S,ID_Tipo FROM tipo_producto WHERE ID_Tipo=$idtipo",$conex_tipo);
		$res=mysql_fetch_array($datostipo[0]);
		echo "<a href='index.php?idtipo=$res[2]'> <- $res[0]</a>";
		while($res[1]!=0){
			$datostipo=Lanzar_Consulta("select Nombre,ID_TipoP_S,ID_Tipo FROM tipo_producto WHERE ID_Tipo=$res[1]",$conex_tipo);
			$res=mysql_fetch_array($datostipo[0]);
			echo "<a href='index.php?idtipo=$res[2]'> <- $res[0]</a>";
		}
		echo " <- <a href='index.php'>Emsa</a> /</font>";
		Desconectar($conex_tipo);
	}else{
		echo "<font face='Arial' size=-2>/ <a href='index.php'>Emsa</a> /</font>";
	}
	//Mostrar por pantalla la ruta con formato c < b < a
}
function Extraer_P_Ruta($vruta,$pos){
	$tamruta=count($vruta);
	return($vruta[$tamruta-$pos]);
}
function S_correo($direccion,$nombre,$empresa,$fecha,$comentario,$tipoproducto){
	echo "EN cons";	
}
function Datos_Empresa(){
	$conex=Conectar();
	$datosempresa=Lanzar_Consulta("select * FROM datos_emsa",$conex);
	$arraydatosmepres=$datosempresa[0];
	$res=mysql_fetch_array($arraydatosmepres);
	echo "<br><font face='Arial' size=-2 ><b>$res[0]</b><br>Telf: $res[1] <br> Direccion: $res[2] <br> Correo: $res[3] <br><b>$res[4]</b></font>";
	Desconectar($conex);
	//Muestra por pantalla los datos de EMsa de la bbdd
}
function Datos_Fabricante($ID_Fab,$modosalida){
	$conex_fab=Conectar();
	$datosfab=Lanzar_Consulta("select * FROM fabricante where ID_Fab=$ID_Fab",$conex_fab); //Mas  delante evolucionaremos esta funcion de forma que segun un segundo parametro muestre el  nombre o bien todos sus datos
	$arraydatosfab=$datosfab[0];
	$res=mysql_fetch_array($arraydatosfab);
	if ($modosalida==1){
		echo "$res[1]";
	}else{
		echo "<font face='Arial' size=-2 ><b>$res[1]</b></font>";	
	}
	Desconectar($conex_fab);
	//Muestra los datos de un fabricante por su ID_Fab
}
function Datos_Tipo($ID_tipo,$modosalida){
	$conex_dat_tip=Conectar();
	$datos_dat_tip=Lanzar_Consulta("select * FROM tipo_producto where ID_Tipo=$ID_tipo",$conex_dat_tip); //Mas  delante evolucionaremos esta funcion de forma que segun un segundo parametro muestre el  nombre o bien todos sus datos
	$arraydatos_dat_tip=$datos_dat_tip[0];
	$res=mysql_fetch_array($arraydatos_dat_tip);
	if ($modosalida==1){
		echo "$res[1]";
	}else{
		if ($modosalida==2){
			echo "
			<table border='0' bgcolor='#F5F5F5' >
				<tr>
					<td>
						<b><font face='Arial' size=-2 >Tipo de Producto :</b> $res[1]<br><br>
							<b>Descripci�n :<b><br><br>";
								if ( $res[2]!="" ){
									echo "$res[2]"; 
								}else{
									echo " Esta categoria de producto no tiene descripci�n"; 
								}
							echo "<br><br></font>
					</td>
				</tr>
			</table>
			";
		}else{
			echo "<font face='Arial' size=-2 ><b>$res[1]</b></font>";
		}
	}
	Desconectar($conex_dat_tip);
	//Muestra lo datos de una tipologia de datos por su ID_Tipo
}
function Mostrar_Version(){
	$conex_ver=Conectar();
	$datosver=Lanzar_Consulta("select * FROM version",$conex_ver);
	$arraydatosver=$datosver[0];
	$res=mysql_fetch_array($arraydatosver);
	echo "<font face='Arial' size=-2 ><b>$res[0]</b></font>";
	Desconectar($conex_ver);
	//Muestra la version del programa GestProduct 2008
}
function Guardar_Datos($conj_datos){
	echo "EN cons";	
	//Guardar los datos del estado del programa
}
function Cargar_Datos(){
	echo "EN cons";
	//Carga los datos de estado del programa
}
function Lanzar_Consulta($consulta,$link){
	
$datos=mysql_query($consulta,$link);
$numfilas=mysql_num_rows($datos);
$resultado[0]=$datos;
$resultado[1]=$numfilas;
return($resultado);

}
function Mostar_Contacto(){
    $conex_cont=Conectar();
	$datoscont=Lanzar_Consulta("select * FROM contacto",$conex_cont);
	$arraydatoscont=$datoscont[0];
	$res=mysql_fetch_array($arraydatoscont);
	echo "<font face='Arial' size=-2 ><b><br>$res[0]</b></font>";
	Desconectar($conex_cont);
}
function Comprobar_BBDD(){
	if(mysql_connect("mysql.telesofa.com","qav939","servidor")){
		return(0);
	}else{
		return(1);
	}
}
?>