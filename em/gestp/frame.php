<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
<link rel='stylesheet' type='text/css' href='css/main.css'></link>
<script type="text/javascript" src='../js/jq.js'></script>
<script type="text/javascript">

	function mostrarNotEnviado(){
		$("#notEnviado").fadeIn("slow");
		setTimeout("ocultarNotEnviado()",5000);
	}
	function ocultarNotEnviado(){
		$("#notEnviado").fadeOut("slow");
	}
	function enviar(){
		var nombre = $("#nombre").val();
		var empresa = $("#empresa").val();
		var mail= $("#mail").val();
		var tipoproducto= $("#tipoproducto").val();
		var fabricante= $("#fabricante").val();
		var producto= $("#producto").val();
		var telefono= $("#telefono").val();
		var comentario= $("#comentario").val();
		var fecha= $("#fecha").val();

		$("#buttonEnviar").attr("disabled", true);
		
		if ( validarEmail(mail) && ningundatoVacio( nombre, empresa , tipoproducto , fabricante , producto , telefono , comentario , fecha )){

			contenido =nombre + " de la empresa "+ empresa + " ha solicitado mas información del producto [ "+ producto  +
			" ] del fabricante [ " + fabricante +
			" ] con categoria [ "+ tipoproducto +" ] a "+ fecha +" con el numero de telefono [ " + telefono +" ] y dejando un comentario: < "+ comentario +">";
			
			$.ajax( {
				type :"POST",
				url :"../pag/ecorreo.php",
				data :"de="+ mail +"&contenido="+contenido,
				success : function(codigo) {
					if(parseInt(codigo) == 1){
						mostrarNotEnviado();
						$("#buttonEnviar").attr("disabled", false);
					}
				}
			});
		}else{
			alert("Datos incorrectos.");
			$("#buttonEnviar").attr("disabled", false);
		}
		
	}

	function  ningundatoVacio( nombre, empresa , tipoproducto , fabricante , producto , telefono , comentario , fecha ){

		if ( nombre != "" && empresa != "" && tipoproducto != "" && fabricante != "" && producto != "" && telefono != "" && comentario != "" && fecha != ""){
			return true;
		}else{
			return false;
		}

	}
	function validarEmail(valor) {

		var filtro=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	    if (filtro.test(valor)){
			return true;
		} else {
			return false;
		}
	}
	
</script>
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
$date=date("Y-m-d H:m:s");
////////////////////

	switch ($modo){
		case 'fab':
			echo "<div class='fabricantes'>Catergoría de Productos:<br>";
						Datos_Tipo($idtipo,0);
						echo "<br><br>Fabricantes:<br>";
						Mostrar_Fabricantes_Tipo($idtipo);
					echo "</div>";						
		break;
		case 'produc':

			echo "<div class='fabricantes'>Catergoría de Productos:<br>";
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
					echo "<div class='fabricantes'>Catergoría de Productos:<br>";
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
									<td><input size='15' type='text' id='nombre'></td>
									<td><font face='Arial' size=-2>Empresa</font></td>
									<td><input  size='15' type='text' id='empresa'></td>
								</tr>
								<tr>
									<td><font face='Arial' size=-2>Tipo Producto</font></td>
									<td><input size='15' type='text' id='tipoproducto' disabled value='";
									Datos_Tipo($idtipo,1);
									echo "'></td>
									<td><font face='Arial' size=-2>Fabricante</font></td>
									<td><input size='15' type='text' id='fabricante' disabled value='";
									Datos_Fabricante($idfab,1);
									echo "'></td>
								</tr>
								<tr >
									<td><font face='Arial' size=-2>Producto</font></td>
									<td><input  size='15' type='text' id='producto' disabled value='";
									Mostrar_Datos_Producto($idproduct,1);
									echo "'></td>
									<td><font face='Arial' size=-2>Telefono</font></td>
									<td><input size='15' type='text' id='telefono'></td>
								</tr>
								<tr >
									<td><font face='Arial' size=-2>Mail</font></td>
									<td><input size='15' type='text' id='mail'></td>
									<td><font face='Arial' size=-2>Comentarios</font></td>
									<td><input size='15' type='text' id='comentario'></td>
								</tr>
								<tr>
									<td><font face='Arial' size=-2>Fecha</font></td>
									<td colspan='2'><input size='15' type='text' id='fecha' disabled value='$date' style='width: 170px;'></td>
									
									<td style='text-align:center;'><input id='buttonEnviar' type='button' value='Enviar' onclick='enviar()'></td>
								</tr>
							</table>
							
						</div>
						
					</div>
					<div id='notEnviado' class='notificacion' style='display:none'>Solicitud enviada con exito.</div>";
					
					
		break;
		case 'dataidtipo':
				Datos_Tipo($idtipo,2);
		break;
		default:
			echo "
			<div class='informacion'>
			<br><br>
			<span class='strong'>
			GestProduct 2009 Emsa - Especialidades Metálicas<span><br>";
			Datos_Empresa();
			echo "</div>";
		break;
	}
				
?>
