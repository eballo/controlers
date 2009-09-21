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
				
				
				
			$res = $db->getFila();
				
			$xmlR ="
			<?xml version='1.0?>
			<mail>
				<id>$res[0]</id>
				<de>$res[1]</de>
				<contenido>$res[2]</contenido>
				<fecha>$res[3]</fecha>
			</mail>
			";
			$db->query("UPDATE correo SET abierto=1 WHERE ID_Corr = $id");
			$db->desconectar();
			echo $xmlR;
				
			break;
		case 4: // Retorna el numero de correos
			$db = new Dbs();
			$db->query("SELECT * from correo ORDER by ID_Corr DESC ");
			echo $db->numFilas();
			$db->desconectar();
			break;
				
		case 5:
			
			$correos = $_POST['ncorreos'];
			
			$db = new Dbs();
			$db->query("SELECT * from correo ORDER by ID_Corr DESC LIMIT $correos ");
			for ($i = 0 ; $i < $db->numFilas(); $i++){
				$res = $db->getFila();
					
				if ($res[4] == 0){
					$leido = 0;
					$cleido = "csinleer";
				}else{
					$leido = 1;
					$cleido = "";
				}
					
				echo "
				<tr id='".$res[0]."' type='label$cleido' leido=".$leido." class='out$cleido' ondblclick=abrirCorreo('".$res[0]."') correo='true'>
					<td width='50px' hight='50px'><img width='50px' hight='50px' src='img/sobre.png'></td>
					<td><div class='header'>De:</div><div class='data'>".$res[1]."</div></td>
					<td><div class='header'>Contenido</div><div class='data'>".$res[2]."</div></td>
					<td><div class='header'>Fecha</div><div class='data'>".$res[3]."</div></td>
					<td width='50px' height='50px'><img onclick=eliminar('".$res[0]."') style='cursor:pointer;' src='img/eliminar.png' width='50px' height='50px'></img></td>
					</tr>
				";
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
			var mailrespuesta ;
			var syncled ;
			var nummailsbandeja =<?php  
					$db = new Dbs();
					$db->query("SELECT * from correo ");
					echo $db->numFilas();
					$db->desconectar();
			?>;

			function syncronizar(){
				
				$.ajax( {
					type :"POST",
					url :"pag/correo.php",
					data :"modo=4",
					success : function(codigo) {
						correosenservidor = parseInt(codigo);
						if (correosenservidor > nummailsbandeja ){
							descargarNuevosCorreos(correosenservidor-nummailsbandeja);
							nummailsbandeja = correosenservidor;
						}else{
							syncled = setTimeout("syncronizar()",10000);
						}
						
					}
				});
				
			}

			function descargarNuevosCorreos( ncorreos ) {
				$.ajax( {
					type :"POST",
					url :"pag/correo.php",
					data :"modo=5&ncorreos="+ncorreos,
					success : function(codigo) {
						$("tr[correo='true']:first").before(codigo);
						syncled = setTimeout("syncronizar()",10000);
					}
				});
				
			}
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
							nummailsbandeja --;
						});
					}
				});
			}
			function mostrarTodos(){
				$("tr[class='outcsinleer']").fadeIn("slow");
				$("tr[class='out']").fadeIn("slow");
				syncled = setTimeout("syncronizar()",10000);
				cambiarBotonPresionado( 0 );
			}
			function mostrarLeidos(){
				$("tr[class='out']").fadeIn("slow");
				$("tr[class='outcsinleer']").fadeOut("slow");
				clearInterval(syncled);
				cambiarBotonPresionado( 1 );
			}	
			function mostrarNoLeidos(){
				$("tr[class='outcsinleer']").fadeIn("slow");
				$("tr[class='out']").fadeOut("slow");
				clearInterval(syncled);
				cambiarBotonPresionado( 2 );
			}
			function cambiarBotonPresionado( m ){
				switch (m){
				case 0:
					$("#correoBotonTodos").attr("class","correoMenuBotoni");
					$("#correoBotonLeidos").attr("class","correoMenuBoton");
					$("#correoBotonNoLeidos").attr("class","correoMenuBoton");
					break;
				case 1: 
					$("#correoBotonTodos").attr("class","correoMenuBoton");
					$("#correoBotonLeidos").attr("class","correoMenuBotoni");
					$("#correoBotonNoLeidos").attr("class","correoMenuBoton");
					break;
				case 2:
					$("#correoBotonTodos").attr("class","correoMenuBoton");
					$("#correoBotonLeidos").attr("class","correoMenuBoton");
					$("#correoBotonNoLeidos").attr("class","correoMenuBotoni");
					break;
				}
			}
			function abrirCorreo(id){
				$.ajax( {
					type :"POST",
					url :"pag/correo.php",
					data :"modo=3&id="+id,
					success : function(codigo) {

						var de = $(codigo).find("de:first").text();
						mailrespuesta = de;
						var contenido = $(codigo).find("contenido:first").text();
						var fecha = $(codigo).find("fecha:first").text();

						$(".CargaMailData").html("<b>De:</b> " + de + "    <b>Fecha:</b> " + fecha);
						$(".CargaMailContenido").text(contenido);
						$(".zonaCargaMail").fadeIn("slow");
						
						$("#"+id).attr("class","out");
						$("#"+id).mouseover(function(){
							$(this).attr("class","over");
						});
						$("#"+id).mouseout(function(){
							$(this).attr("class","out");
						});out

					}
				});
			}

			function cerrarCorreo(){
				$(".zonaCargaMail").fadeOut("slow");
			}
			
			function responderCorreo(){
				document.location = "mailto:" + mailrespuesta;
			}
			$(function(){
//				$("html").css("overflow","hidden");
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
					$(this).attr("class","outcsinleer");
					});



				$("#imagenAlta").change(function(){
						$("#formAlta").submit();
						$("input[type='button']").attr("disabled", true);
						validante = setInterval("validarImg()",3000);
					});

				syncled = setTimeout("syncronizar()",10000);
				
				});
</script>

<div class='contenedor'>
<table class='contenedorCorreo' height='100%' border='0' cellpadding="0"
	cellspacing="0">
	<tr>
		<td class='menuCorreo'>
		<div class='contenedorMenuCorreo'>
			<div id='correoBotonTodos' class='correoMenuBotoni' style='top: 0px; left: 0px;' onclick='mostrarTodos()'>Todos</div>
			<div id='correoBotonLeidos' class='correoMenuBoton' style='top: 25px; left: 0px;' onclick='mostrarLeidos()'>Leidos</div>
			<div id='correoBotonNoLeidos' class='correoMenuBoton' style='top: 50px; left: 0px;' onclick='mostrarNoLeidos()'>No leidos</div>
		</div>
		</td>
		<td class='listaCorreo'>
		<table id='mainTable' class='correo' cellpadding="0" cellspacing="0">
		<?php
		$db = new Dbs();
		$db->query("SELECT * from correo ORDER by ID_Corr DESC ");
		for ($i = 0 ; $i < $db->numFilas(); $i++){
			$res = $db->getFila();

			if ($res[4] == 0){
				$leido = 0;
				$cleido = "csinleer";
			}else{
				$leido = 1;
				$cleido = "";
			}

			echo "
				<tr id='".$res[0]."' type='label$cleido' leido=".$leido." class='out$cleido' ondblclick=abrirCorreo('".$res[0]."') correo='true'>
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
<div class='pieCorreo'></div>

<div class='zonaCargaMail'>
<div class='bordeCargaMail'>
<div class='CargaMail'>
<div class='CargaMailData'></div>
<div class='CargaMailContenido'></div>
<div class='CargaMailBotones'><span class='CargaMailBoton'
	onclick='responderCorreo()'>Responder</span><span
	class='CargaMailBoton' onclick='cerrarCorreo()'>Cerrar</span></div>
</div>
</div>
</div>


<div class='eliminar' id='eliminar'>
<div class='textEliminar'>¿¿Esta seguro que desea eliminar el correo
seleccionado??.</div>
<input type='button' value='Si' onclick='celiminar()' /> <input
	type='button' value='No' onclick='cancelarEliminar()' /></div>


