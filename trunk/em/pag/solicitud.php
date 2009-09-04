<?php
	include "includes/funciones.php";
	include "class/classes.php";
	//////////////////////////
	$error=0;
	echo "<table border='0' bgcolor='#F5F5F5'><tr><td><font face='Arial' size='-1'>";
	if ( $_GET['nombre']!="" ){
		$nombre=$_GET['nombre'];
	}else{
		echo "<br>Falta : nombre";
		$error="1";
	}
	if ( $_GET['empresa']!="" ){
		$empresa=$_GET['empresa'];
	}else{
		echo "<br>Falta : empresa";
		$error="1";
	}
	if ( $_GET['tipoproducto']!="" ){
		$tipoproducto=$_GET['tipoproducto'];
	}else{
		echo "<br>Falta : tipoproducto";
		$error="1";
	}
	if ( $_GET['fabricante']!="" ){
		$fabricante=$_GET['fabricante'];
	}else{
		echo "<br>Falta : fabricante";
		$error="1";
	}
	if ( $_GET['produc']!="" ){
		$produc=$_GET['produc'];
	}else{
		/*echo "<br>Falta : produc";
		$error="1";*/
		$produc='Null';
	}
	if ( $_GET['telefono']!="" ){
		$telefono=$_GET['telefono'];
	}else{
		echo "<br>Falta : telefono";
		$error="1";
	}
	if ( $_GET['direccion']!="" ){
		$direccion=$_GET['direccion'];
	}else{
		echo "<br>Falta : direccion";
		$error="1";
	}
	if ( $_GET['comentario']!="" ){
		$comentario=$_GET['comentario'];
	}else{
		echo "<br>Falta : comentario";
		$error="1";
	}
	if ( $_GET['fecha']!="" ){
		$fecha=$_GET['fecha'];
	}else{
		echo "<br>Falta : fecha";
		$error="1";
	}
	echo "</td></tr></table></font>";
	if( $error == 0 ){
		$contenido="
		<font face='Arial' size='-1'>
			<b>Nombre</b> : $nombre <br>
			<b>Empresa</b> : $empresa <br>
			<b>Tipoproducto</b> : $tipoproducto <br>
			<b>Fabricante</b> : $fabricante <br>
			<b>Producto</b> : $produc <br> 
			<b>Telefono</b> : $telefono <br>
			<b>Direccion</b> : $direccion <br>
			<b>Comentario</b> : $comentario <br>
			<b>Fecha</b> : $fecha <br>
		</font>
		";
		
	}else{
		echo "<br><br><font face='Arial' size='-1'>Faltan datos por rellenar , comprueba el formulario</font>";
	}

?>