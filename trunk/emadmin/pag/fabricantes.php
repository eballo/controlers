<?php 
require_once '../class/class.Dbs.php';


if (isset($_POST['modo'])){
	$m = $_POST['modo'];
	
	switch($m){
		case 1: //Eliminar
			$db = new Dbs();
			$db->query("SELECT * from fabricante");
			$db->desconectar();
			break;
			
		case 2: //Agregar
			
			break;
	}
	die;	
}


?>

<script type="text/javascript">

			var ideliminar = 0;

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
					url :"pag/fabricantes.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							$("#eliminar").fadeOut("slow");
						});
					}
				});
			}
			
			$(function(){

				
				$("tr").mouseover(function(){
					$(this).attr("class","over");
					});
				$("tr").mouseout(function(){
					$(this).attr("class","out");
					});
				});
</script>
<div class='eliminar' id='eliminar'>
	<div class='textEliminar'>¿¿Esta seguro que desea eliminar el fabricante y todos sus productos??.</div>
	<input type='button' value='Si' onclick='celiminar()' /> 
	<input type='button' value='No' onclick='cancelarEliminar()' />
</div>
<table class='fabricante' cellpadding="0" cellspacing="0">
<?php
	$db = new Dbs();
	$db->query("SELECT * from fabricante");
	
	for ($i = 0 ; $i < $db->numFilas(); $i++){
		
		$res = $db->getFila();
		
		echo "
		<tr id='".$res[0]."'>
			<td width='80px' height='40px'><div class='imagenfab'><img class='imagenfab' style='opacity:0.8;cursor:pointer;' src='../em/gestp/".$res[4]."' width=80px height=40px'/></div></td>
			<td><div class='header'>Nombre</div><div class='data'>".$res[1]."</div></td>
			<td><div class='header'>Direccion</div><div class='data'>".$res[2]."</div></td>
			<td><div class='header'>Otros Datos</div><div class='data'>".$res[3]."</div></td>
			<td width='50px' height='50px'><img onclick=eliminar('".$res[0]."') style='opacity:0.8;cursor:pointer;' src='img/eliminar.png' width='50px' height='50px'></img></td>
			</tr>
		";
		
	}
	
	$db->desconectar();
?>
</table>	



