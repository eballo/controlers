<?php
require_once '../seguridad/seguridad.php';
require_once '../class/class.Dbs.php';


if (isset($_POST['modo'])){
	$m = $_POST['modo'];
	$id = $_POST['id'];
	switch($m){
		case 1: //Eliminar
			$db = new Dbs();
			$db->query("DELETE from correo where ID_Corr = $id ");
			$db->desconectar();
			break;
				
		case 2: //Agregar
//			
//			$titulo = $_POST['titular'];
//			$contenido = $_POST['contenido'];
//			$fecha = date("Y-m-d H:m:s");
//			
//			$db = new Dbs();
//			$db->query("INSERT into correo values ('','$titulo','$contenido','$fecha')");
//			$db->query("SELECT * FROM noticia ORDER BY ID_Not DESC LIMIT 0 , 1  ");
//			$id=$db->getFila();
//			$db->desconectar();
			
		echo "
			<tr id='".$id[0]."'>
				<td><div class='header'>Titular</div><div class='data'>".$titulo."</div></td>
				<td><div class='header'>Descripción</div><div class='data'>".$contenido."</div></td>
				<td><div class='header'>Fecha</div><div class='data'>".$fecha."</div></td>
				<td width='30px' height='30px'><img onclick=eliminar('".$id[0]."') style='cursor:pointer;' src='img/eliminar.png' width='30px' height='30px'></img></td>
				</tr>
			";
			
			break;
		case 3://Cargar correo;
			
			$db = new Dbs();
			$db->query("SELECT * from correo where ID_Corr = $id ");
			$db->desconectar();
			
			$xmlR = "
			<?xml version='1.0?>
			<mail>
				<de></de>
				<contenido></contenido>
				<fecha></fecha>
			</mail>
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
					url :"pag/correo.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							
						});
					}
				});
			}

			function abrirCorreo(){
				$.ajax( {
					type :"POST",
					url :"pag/correo.php",
					data :"modo=1&id="+ideliminar,
					success : function(codigo) {
						$("#"+ideliminar).fadeOut("slow",function(){
							$(this).remove();
							
						});
					}
				});
			}

			$(function(){
				$("html").css("overflow","hidden");
				$("tr[type='label']").mouseover(function(){
					$(this).attr("class","over");
					});
				$("tr[type='label']").mouseout(function(){
					$(this).attr("class","out");
					});
				
				$("tr[type='labelcsinleer']").mouseover(function(){
					$(this).attr("class","over");
					});
				$("tr[type='labelcsinleer']").mouseout(function(){
					$(this).attr("class","csinleer");
					});

				$("div[class='correoMenuBoton']").mouseover(function(){
					$(this).attr("class","correoMenuBotoni");
					});
				$("div[class='correoMenuBoton']").mouseout(function(){
					$(this).attr("class","correoMenuBoton");
					});

				$("#imagenAlta").change(function(){
						$("#formAlta").submit();
						$("input[type='button']").attr("disabled", true);
						validante = setInterval("validarImg()",3000);
					});
				});
</script>




<div class='contenedor'>
	<table class='contenedorCorreo' height='100%' border='0' cellpadding="0" cellspacing="0">
	<tr>
	<td class='menuCorreo'>
		<div class='correoMenuBotoni' style='top: 0px;left:0px;'>Todos</div>
		<div class='correoMenuBoton' style='top: 25px;left:0px;'>Leidos</div>
		<div class='correoMenuBoton' style='top: 50px;left:0px;'>No leidos</div>
	</td>
	<td class='listaCorreo'>
		<table id='mainTable' class='correo' cellpadding="0" cellspacing="0">
				<?php
		$db = new Dbs();
		$db->query("SELECT * from correo ORDER by fecha DESC ");
		for ($i = 0 ; $i < $db->numFilas(); $i++){
			$res = $db->getFila();
			
			if ($res[4] == 0){
				$leido = 0;
				$cleido = "csinleer";
			}else{
				$leido = 1;
			}
			
			echo "
				<tr id='".$res[0]."' type='label$cleido' leido=".$leido." class='$cleido'>
					<td width='50px' hight='50px'><img width='50px' hight='50px' src='img/sobre.png'></td>
					<td><div class='header'>De:</div><div class='data'>".$res[1]."</div></td>
					<td><div class='header'>Contenido</div><div class='data'>".$res[2]."</div></td>
					<td><div class='header'>Fecha</div><div class='data'>".$res[3]."</div></td>
					<td width='50px' height='50px'><img onclick=eliminar('".$res[0]."') style='cursor:pointer;' src='img/eliminar.png' width='50px' height='50px'></img></td>
					</tr>
				";
		}
		$db->desconectar();
		?>
		</table>
	</td>
	</tr>
	</table>
</div>



<div class='eliminar' id='eliminar'>
<div class='textEliminar'>¿¿Esta seguro que desea eliminar el correo seleccionado??.</div>
<input type='button' value='Si' onclick='celiminar()' /> <input
	type='button' value='No' onclick='cancelarEliminar()' /></div>


