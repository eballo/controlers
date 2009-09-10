<?php
require_once '../seguridad/seguridad.php';
require_once '../class/class.Dbs.php';


if (isset($_POST['modo'])){
	$m = $_POST['modo'];
	$id = $_POST['id'];
	switch($m){
		case 1: //Eliminar
			$db = new Dbs();
			$db->query("DELETE from tipo_producto where ID_Tipo = $id ");
 			$db->query("DELETE from tipo_producto where ID_TipoP_S = $id ");
			$db->desconectar();
			break;
				
		case 2: //Agregar
			
			$nombre = $_POST['nombre'];
			$otrosdatos = $_POST['otrosdatos'];
			$depende = $_POST['depende'];
			
			$db = new Dbs();
			$db->query("INSERT into tipo_producto values ('','$nombre','$otrosdatos','',$depende)");
			$db->query("SELECT * FROM tipo_producto ORDER BY ID_Tipo DESC LIMIT 0 , 1  ");
			$id=$db->getFila();
			$db->desconectar();
			
			$db2 = new Dbs();
			$db2->query("SELECT nombre from tipo_producto where ID_Tipo = $id[4] ");
			$depende = $db2->getFila();
	
			$db2->desconectar();
			
			echo "
				<tr id='".$id[0]."'>
					<td><div class='header'>Nombre</div><div class='data'>".$nombre."</div></td>
					<td><div class='header'>Otros Datos</div><div class='data'>".$otrosdatos."</div></td>
					<td><div class='header'>Depende de</div><div class='data'>".$depende[0]."</div></td>
					<td width='30px' height='30px'><img onclick=eliminar('".$id[0]."') style='cursor:pointer;' src='img/eliminar.png' width='30px' height='30px'></img></td>
					</tr>
				";
			
			break;
		case 3:
			$db = new Dbs();
			$db->query("SELECT * from tipo_producto ");
			
			for ($i = 0 ; $i < $db->numFilas(); $i++){
			
				$res = $db->getFila();
			
				if ($num[0] == 0 ){
					echo "<option value='$res[0]'>$res[1]</option>";
				}
			}
			$db->desconectar();
			break;
	}
	die;
}


?>

<script type="text/javascript">

			var ideliminar = 0;
			var validante ;
			var imagenAlta ;
			function eliminar( id ){
				ideliminar = id;
				$("#eliminar").fadeIn("slow");
			}
			function cancelarEliminar(){
				$("#eliminar").fadeOut("slow");
			}
			function celiminar(){
				

				$.ajax( {
					type :"POST",
					url :"pag/categorias.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							$("#eliminar").fadeOut("slow");
							actualizarDependencias();
						});
					}
				});
			}

			function alta( id ){
				ideliminar = id;
				
				$("#formularioAlta").fadeIn("slow");
			}
			function cancelarAlta(){
				$("#formularioAlta").fadeOut("slow");
			}

			function actualizarDependencias(){
				$.ajax( {
					type :"POST",
					url :"pag/categorias.php",
					data :"modo=3",
					success : function(codigo) {
						$("#fdepende").empty();
						$("#fdepende").append(codigo);
					}
				});
			}
			function calta(){

				var nombre = $("#fnombre").val();
				var otrosdatos = $("#fotrosdatos").val();
				var depende = $("#fdepende").val();
				
				$.ajax( {
					type :"POST",
					url :"pag/categorias.php",
					data :"modo=2&nombre="+nombre+"&depende="+depende+"&otrosdatos="+otrosdatos,
					success : function(codigo) {
						$("#mainTable").append(codigo);
						$("#formularioAlta").fadeOut("slow");
						actualizarDependencias();
					}
				});
			}
			
			function validarImg(){
				$.ajax( {
					type :"POST",
					url :"pag/vimg.php",
					data :"",
					success : function(codigo) {
						if ( codigo != 2 && codigo != 0 ){
							cargarImagen(codigo);
							imagenAlta = codigo;
							clearInterval(validante);
							$("input[type='button']").attr("disabled", false);
						}else{
							if ( codigo == 2){
								cargarImagen("img/error.png");
								clearInterval(validante);
							}
						}
					}
				});
			}
			
			function cargarImagen( url ){
				$("#imgAltaForm").fadeOut("slow", function(){
						$("#imgAltaForm").attr("src", url);
						$("#imgAltaForm").fadeIn("slow");
					});
			}
			$(function(){

				$("tr").mouseover(function(){
					$(this).attr("class","over");
					});
				$("tr").mouseout(function(){
					$(this).attr("class","out");
					});

				$("#imagenAlta").change(function(){
						$("#formAlta").submit();
						$("input[type='button']").attr("disabled", true);
						validante = setInterval("validarImg()",3000);
					});
				});
</script>


<div class='panelHeramientas'>
<div class='herramienta' onclick='alta()'>Añadir</div>
</div>
<div class='eliminar' id='eliminar'>
<div class='textEliminar'>¿¿Esta seguro que desea eliminar la categoria de productos y todas sus dependencias??.</div>
<input type='button' value='Si' onclick='celiminar()' /> <input
	type='button' value='No' onclick='cancelarEliminar()' /></div>
<div class='formularioAlta' id='formularioAlta'>
	<div class='textFormAlta'>Formulario de alta categoria<br><br>
	<div class='textFormAlta'>Nombre <input type='text' id='fnombre'/></div>
	<div class='textFormAlta'>Otros Datos <input type='text' id='fotrosdatos'/></div>
	<div class='textFormAlta'>Depende de  <select id='fdepende'><option value='0'>De nadie </option>
<?php 
$db = new Dbs();
$db->query("SELECT * from tipo_producto ");

for ($i = 0 ; $i < $db->numFilas(); $i++){

	$res = $db->getFila();

	if ($num[0] == 0 ){
		echo "<option value='$res[0]'>$res[1]</option>";
	}
}


?>
</select> </div><br><br>
	<div style='text-align:center;'><input type='button' value='Guardar' onclick='calta()'><input type='button' value='Cancelar' onclick='cancelarAlta()'></div>
	
	<iframe id='ifimg' name='imagen' style='display: none' ></iframe>
	</div>
</div>
<table id='mainTable' class='fabricante' cellpadding="0" cellspacing="0">
<?php

$db->query("SELECT * from tipo_producto ORDER by ID_TipoP_S ");
$db2 = new Dbs();


for ($i = 0 ; $i < $db->numFilas(); $i++){

	$res = $db->getFila();
	$db2->query("SELECT nombre from tipo_producto where ID_Tipo = $res[4] ");
	$depende = $db2->getFila();
	
	echo "
		<tr id='".$res[0]."'>
			<td><div class='header'>Nombre</div><div class='data'>".$res[1]."</div></td>
			<td><div class='header'>Otros Datos</div><div class='data'>".$res[2]."</div></td>
			<td><div class='header'>Depende de</div><div class='data'>".$depende[0]."</div></td>
			<td width='30px' height='30px'><img onclick=eliminar('".$res[0]."') style='cursor:pointer;' src='img/eliminar.png' width='30px' height='30px'></img></td>
			</tr>
		";

}

$db->desconectar();
$db2->desconectar();
?>
</table>



