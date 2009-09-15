<?php
require_once '../seguridad/seguridad.php';
require_once '../class/class.Dbs.php';


if (isset($_POST['modo'])){
	$m = $_POST['modo'];
	$id = $_POST['id'];
	switch($m){
		case 1: //Eliminar
			$db = new Dbs();
			$db->query("DELETE from noticia where ID_Not = $id ");
			$db->desconectar();
			break;
				
		case 2: //Agregar
			
			$titulo = $_POST['titular'];
			$contenido = $_POST['contenido'];
			$fecha = date("Y-m-d H:m:s");
			
			$db = new Dbs();
			$db->query("INSERT into noticia values ('','$titulo','$contenido','$fecha')");
			$db->query("SELECT * FROM noticia ORDER BY ID_Not DESC LIMIT 0 , 1  ");
			$id=$db->getFila();
			$db->desconectar();
			
		echo "
			<tr id='".$id[0]."'>
				<td><div class='header'>Titular</div><div class='data'>".$titulo."</div></td>
				<td><div class='header'>Descripción</div><div class='data'>".$contenido."</div></td>
				<td><div class='header'>Fecha</div><div class='data'>".$fecha."</div></td>
				<td width='30px' height='30px'><img onclick=eliminar('".$id[0]."') style='cursor:pointer;' src='img/eliminar.png' width='30px' height='30px'></img></td>
				</tr>
			";
			
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
				$("#eliminar").fadeOut("slow");

				$.ajax( {
					type :"POST",
					url :"pag/noticias.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							
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

				var titular = $("#ftitular").val();
				var contenido = $("#fcontenido").val();

				
				$("#formularioAlta").fadeOut("slow");
				$.ajax( {
					type :"POST",
					url :"pag/noticias.php",
					data :"modo=2&titular="+titular+"&contenido="+contenido,
					success : function(codigo) {
						$("#mainTable").prepend(codigo);
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
<div class='formularioAlta' id='formularioAlta' >
	<div class='textFormAlta'>Formulario de alta noticia<br><br>
	<div class='textFormAlta'>Titular <input type='text' id='ftitular'/></div>
	<div class='textFormAlta'>Contenido <input type='text' id='fcontenido'/></div><br><br>
	<div style='text-align:center;'><input type='button' value='Guardar' onclick='calta()'><input type='button' value='Cancelar' onclick='cancelarAlta()'></div>
	</div>
</div>
<table id='mainTable' class='fabricante' cellpadding="0" cellspacing="0">
<?php
$db = new Dbs();
$db->query("SELECT * from noticia ORDER by fecha DESC ");


for ($i = 0 ; $i < $db->numFilas(); $i++){

	$res = $db->getFila();

	echo "
		<tr id='".$res[0]."'>
			<td><div class='header'>Titular</div><div class='data'>".$res[1]."</div></td>
			<td><div class='header'>Descripción</div><div class='data'>".$res[2]."</div></td>
			<td><div class='header'>Fecha</div><div class='data'>".$res[3]."</div></td>
			<td width='30px' height='30px'><img onclick=eliminar('".$res[0]."') style='cursor:pointer;' src='img/eliminar.png' width='30px' height='30px'></img></td>
			</tr>
		";

}

$db->desconectar();
?>
</table>



