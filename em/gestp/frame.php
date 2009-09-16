<link rel='stylesheet' type='text/css' href='css/main.css'></link>
<?php
include "includes/funciones.php";

//////////////////////////

if ( isset($_GET['modo'])){
	$modo=$_GET['modo'];
}else{
	$modo="ini";
}
if ( isset($_GET['idtipo'])){
	$idtipo=$_GET['idtipo'];
}else{
	$idtipo="ini";
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
$date=date('l, d. F Y');
////////////////////

	switch ($modo){
		case 'fab':
			echo "<div class='fabricantes'>Catergoria de Productos:<br>";
						Datos_Tipo($idtipo,0);
						echo "<br><br>Fabricantes:<br>";
						Mostrar_Fabricantes_Tipo($idtipo);
					echo "</div>";						
		break;
		case 'produc':

			echo "<div class='fabricantes'>Catergoria de Productos:<br>";
						Datos_Tipo($idtipo,0);
						echo "<br><br>Fabricantes:<br>";
						Mostrar_Fabricantes_Tipo($idtipo);
					echo "</div>
					<div class='productos'>Productos de <br>";
						Datos_Fabricante($idfab,0);
						echo " :<br><br>";
						Mostrar_Producto_Fabricante($idfab,$idtipo);
					echo "</div>";

		break;
		case 'dataproduct':
					if ( Producto_En_Oferta($idproduct)){
						echo "<div class='alertaOferta'></div>";
					}	
					echo "<div class='fabricantes'>Catergoria de Productos:<br>";
						Datos_Tipo($idtipo,0);
						echo "<br><br>Fabricantes:<br>";
						Mostrar_Fabricantes_Tipo($idtipo);
					echo "</div>
					<div class='productos'>Productos de <br>";
						Datos_Fabricante($idfab,0);
						echo " :<br><br>";
						Mostrar_Producto_Fabricante($idfab,$idtipo);
					echo "</div>
					<div class='datosProducto'>";
						Mostrar_Datos_Producto($idproduct,0);
						echo "
						<div class='solinfo'>
							<table style='border:0'>
								<tr >
									<td><font face='Arial' size=-2>Nombre</font></td>
									<td><input size='15' type='text' name='nombre'></td>
									<td><font face='Arial' size=-2>Empresa</font></td>
									<td><input  size='15' type='text' name='empresa'></td>
								</tr>
								<tr>
									<td><font face='Arial' size=-2>Tipo Producto</font></td>
									<td><input size='15' type='text' name='tipoproducto' disabled value='";
									Datos_Tipo($idtipo,1);
									echo "'></td>
									<td><font face='Arial' size=-2>Fabricante</font></td>
									<td><input size='15' type='text' name='fabricante' disabled value='";
									Datos_Fabricante($idfab,1);
									echo "'></td>
								</tr>
								<tr >
									<td><font face='Arial' size=-2>Producto</font></td>
									<td><input  size='15' type='text' name='produc' disabled value='";
									Mostrar_Datos_Producto($idproduct,1);
									echo "'></td>
									<td><font face='Arial' size=-2>Telefono</font></td>
									<td><input size='15' type='text' name='telefono'></td>
								</tr>
								<tr >
									<td><font face='Arial' size=-2>Dirección</font></td>
									<td><input size='15' type='text' name='direccion'></td>
									<td><font face='Arial' size=-2>Comentarios</font></td>
									<td><input size='15' type='text' name='comentario'></td>
								</tr>
								<tr>
									<td><font face='Arial' size=-2>Fecha</font></td>
									<td><input size='15' type='text' name='fecha' disabled value='$date'></td>
									<td> </td>
									<td><input type='submit' value='Enviar'></td>
								</tr>
							</table>
						</div>

					</div>";
					
		break;
		case 'dataidtipo':
				Datos_Tipo($idtipo,2);
		break;
		default:
			echo "
			<div class='informacion'>
			<br><br>
			<span class='strong'>
			GestProduct 2009 Emsa - Especialidades Metalicas<span><br>";
			Datos_Empresa();
			echo "</div>";
		break;
	}
				
?>
