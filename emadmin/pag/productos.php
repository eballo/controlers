<?php
require_once '../seguridad/seguridad.php';
require_once '../class/class.Dbs.php';


if (isset($_POST['modo'])){
	$m = $_POST['modo'];
	$id = $_POST['id'];
	switch($m){
		case 1: //Eliminar
			$db = new Dbs();
			$db->query("DELETE from producto where ID_Produc = $id ");
			$db->desconectar();
			break;

		case 2: //Agregar

			$nombre = $_POST['nombre'];
			$direccion = $_POST['direccion'];
			$otrosdatos = $_POST['otrosdatos'];
			$img = $_POST['img'];

			$db = new Dbs();
			$db->query("INSERT into fabricante values ('','$nombre','$direccion','$otrosdatos','$img')");
			$db->query("SELECT * FROM fabricante ORDER BY ID_Fab DESC LIMIT 0 , 1  ");
			$id=$db->getFila();
			$db->desconectar();

			echo "<tr id='".$id[0]."'>
			<td width='80px' height='40px'><div class='imagenfab'><img class='imagenfab' style='opacity:0.8;cursor:pointer;' src='../em/gestp/".$img."' width=80px height=40px'/></div></td>
			<td><div class='header'>Nombre</div><div class='data'>".$nombre."</div></td>
			<td><div class='header'>Direccion</div><div class='data'>".$direccion."</div></td>
			<td><div class='header'>Otros Datos</div><div class='data'>".$otrosdatos."</div></td>
			<td width='50px' height='50px'><img onclick=eliminar('$id[0]') style='opacity:0.8;cursor:pointer;' src='img/eliminar.png' width='50px' height='50px'></img></td>
			</tr>";

			break;
		case 3:
			$oferta = $_POST['oferta'];
			$id = $_POST['id'];
			
			$db = new Dbs();
			$db->query("UPDATE producto SET oferta = $oferta WHERE ID_Produc = $id ");
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
					url :"pag/productos.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							$("#eliminar").fadeOut("slow");
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
			function calta(){

				var nombre = $("#fnombre").val();
				var direccion = $("#fdireccion").val();
				var otrosdatos = $("#fotrosdatos").val();
				
				$.ajax( {
					type :"POST",
					url :"pag/prductos.php",
					data :"modo=2&nombre="+nombre+"&direccion="+direccion+"&otrosdatos="+otrosdatos+"&img="+imagenAlta,
					success : function(codigo) {
						$("#mainTable").append(codigo);
						$("#formularioAlta").fadeOut("slow");
					}
				});
			}

			function cambiarOferta(id){
				
					if ($('#oferta'+id).is(':checked')){
						oferta = 1;
					}else{
						oferta = 0;
					}
					
					$.ajax( {
						type :"POST",
						url :"pag/productos.php",
						data :"modo=3&id="+id+"&oferta="+oferta,
						success : function(codigo) {
							$("#mainTable").append(codigo);
							$("#formularioAlta").fadeOut("slow");
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
				$('input:checkbox:not([safari])').checkbox();
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
<div class='textEliminar'>¿¿Esta seguro que desea eliminar el producto??.</div>
<input type='button' value='Si' onclick='celiminar()' /> <input
	type='button' value='No' onclick='cancelarEliminar()' /></div>
<div class='formularioAlta' id='formularioAlta'>
<div class='textFormAlta'>Formulario de alta producto<br>
<br>
<div class='textFormAlta'>Nombre<input type='text' id='fnombre' /></div>
<div class='textFormAlta'>Dirección<input type='text' id='fdireccion' /></div>
<div class='textFormAlta'>Otros Datos<input type='text' id='fotrosdatos' /></div>
<div id='zonaImgForm'><img id='imgAltaForm' style='display: none'
	width='80px' height='40px' /></div>
<form action='pag/uploadimg.php' target='imagen' id='formAlta'
	method='POST' enctype="multipart/form-data">
<div class='textFormAlta'>Imagen<input type='file' name='fileUpload'
	id='imagenAlta' /></div>
<br>
<br>
</form>

<div style='text-align: center;'><input type='button' value='Guardar'
	onclick='calta()'><input type='button' value='Cancelar'
	onclick='cancelarAlta()'></div>

<iframe id='ifimg' name='imagen' style='display: none'></iframe></div>
</div>
<table id='mainTable' class='fabricante' cellpadding="0" cellspacing="0">
<?php
$db = new Dbs();
$db->query("SELECT * from producto ORDER by Tipo , ID_Fab");

for ($i = 0 ; $i < $db->numFilas(); $i++){
	
	$res = $db->getFila();
	
	$db3 = new Dbs();
	$db3->query("SELECT nombre from tipo_producto WHERE ID_Tipo = $res[5]");
	$tipoproducto = $db3->getFila();
	$db3->query("SELECT nombre from fabricante WHERE ID_Fab = $res[0]");
	$fabricante =$db3->getFila();
    
	if ($res[6] == 1){
		$estado = "checked";
	}else{
		$estado = "";
	}
	

	echo "
		<tr id='".$res[1]."'>
			<td width='80px' height='80px'>
				<div class='imagenpro'>
					<img class='imagenpro' style='cursor:pointer;' src='../em/gestp/".$res[4]."' width=70px height=70px'/>
				</div>
			</td>
			<td><div class='header'>Nombre</div><div class='data'>".$res[2]."</div></td>
			<td><div class='header'>Descripción</div><div class='data'>".$res[3]."</div></td>
			<td><div class='header'>Fabricante</div><div class='data'>".$fabricante[0]."</div></td>
			<td><div class='header'>Tipo de producto</div><div class='data'>".$tipoproducto[0]."</div></td>
			<td><div class='header'>Oferta</div><input id='oferta".$res[1]."' type='checkbox' $estado onclick=cambiarOferta('".$res[1]."') /></td>
			<td width='50px' height='50px'><img onclick=eliminar('".$res[1]."') style='cursor:pointer;' src='img/eliminar.png' width='50px' height='50px'></img></td>
			</tr>
		";

}
$db3->desconectar();
$db->desconectar();

?>
</table>


