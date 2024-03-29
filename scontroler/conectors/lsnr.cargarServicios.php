<?php
//////////////////////////////////////////////////
require_once '../includes/textResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Cargar_Servicios","TEXT","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$servicios = new Servicios();
	$serviciosArray = $servicios->getServicios();

	if (count($serviciosArray) > 0){
		foreach ($serviciosArray as $serv ){

			$ret.="
		<div class='servicioMain' id='main".$serv->getNombre()."'>
		<table>
			<tr>
				<td>
					<div class='servicioInfo'  id='info".$serv->getNombre()."'>
						<table class='expand'>
							<tr>
								<td rowspan='2'><img id='estadoImg".$serv->getNombre()."' src='img/start.png' /></td>
								<td style='width:100%'><b id='nombreServicio'>".$serv->getNombre()."</b></td>
								<td rowspan='2'>";

							if (! $_SESSION['hosts'][$serv->getNombre().$_SESSION['directorio']] ){
								$ret.="<img id='estadoSeguridad".$serv->getNombre()."' servicio='".$serv->getNombre()."' src='img/canda.png' class='estadoSeguridad' />";
							}
							$ret.="</td>
							</tr>
							<tr>
								<td>".$serv->getDescripcion()."</td>
							</tr>
							
						</table>
					</div>
				</td>
				<td>
					<div class='servicioDatos'>
					<table class='expand'>
							<tr>
								<td rowspan='2'><div class='contenedorEstadoServer'><img class='serverImg' src='img/serv.png'  /><img id='serverRunning".$serv->getNombre()."' class='serverRunning' src='img/running.png'  /></div></td>
								<td><b>Host:</b> ".$serv->getHost()."</td>
								<td><b>Puerto:</b> ".$serv->getPuerto()."</td>
								<td rowspan='2'>
										<table id='opcionesServicio".$serv->getNombre()."'>
											<tr><td><div class='boton' onclick=parar('".$serv->getNombre()."')>Parar</div></td></tr>
											<tr><td><div class='boton' onclick=reiniciar('".$serv->getNombre()."')>Reiniciar</div></td></tr>
										</table>
								</td>
								<td rowspan='2'>
										<table>
											<tr><td><div class='minBoton' onclick=eliminarServicio('".$serv->getNombre()."')>x</div></td></tr>
											<tr><td ><div class='minBoton' id='despcButon".$serv->getNombre()."' onclick=desplegarPanel('".$serv->getNombre()."')>+</div></td></tr>
										</table>
								</td>
							</tr>
							<tr>
								<td><b>Usuario:</b> ".$serv->getUser()." <img src='img/sec.png'></td>
								<td><b>Servicio:</b> ".$serv->getNombreProceso()."</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
		<div class='desplegableComandos' id='desp".$serv->getNombre()."' idorg='".$serv->getNombre()."'>
			<div class='contenedorComandos' id='contenedorComandos".$serv->getNombre()."'>
				";
			$comandos = $serv->getComandos();
			$d=0;

			if (count($comandos) > 0){
				$cont=0;
				foreach ($comandos as $cmd){
					$d++;
					if ( $d % 2 == 0 ){
						$tipo="comando";
					}else{
						$tipo="comandoi";
					}
					$ret.="<div class='$tipo' numcmd='$cont' tipo='cmd'><table>
							<tr>
								<td><b id='nombreCmd'>".$cmd->getNombre()."</b></td>
								<td>[<span>".$cmd->getCmd()."</span>]</td>
								<td>
								<img onclick=\"ejecutarCmd('".$serv->getNombre()."', $cont );\" style='cursor:pointer;margin-left:10px' src='img/run.png'/>
								<img src='img/papelera.png' style='cursor:pointer;' onclick=\"delCmd('".$serv->getNombre()."', $cont)\"></td>
								<img  id='icon".$serv->getNombre().$cont."' src='img/informe.png' style='cursor:pointer;display:none' onclick=\"mostrarInformeCmd('".$serv->getNombre().$cont."')\"><div id='".$serv->getNombre().$cont."' style='display:none' rescmd=''></div></td>
								</tr>
						</table>
					</div>";
					$cont++;
				}
			}
			$ret.="
			</div>
			<div  class='pieContenedorComandos'><img style='cursor:pointer' src='img/mas.png' onclick=addCmd('".$serv->getNombre()."') /> Add
			<input type='text' id='inputCmdNombre".$serv->getNombre()."' onclick=vaciari('inputCmdNombre".$serv->getNombre()."') value='< nombre >'/>
			<input type='text' id='inputCmd".$serv->getNombre()."' onclick=vaciari('inputCmd".$serv->getNombre()."') value=' < comando >'/>
			</div>
		</div>
		</div>";
		}
	}else{
		$ret.="Nungún servicio";
	}

	//codigo para transformar lo candados en dropables
	$ret.="
	<script type='text/javascript'>
		$(function(){
	 		var options = {};
	
			$(\".estadoSeguridad\").each(function(){
				$(this).droppable({
						drop: function(event, ui) {
							$(\"#\" + ui.draggable.attr('id') ).effect(\"explode\", options, 500);
							var password = $(\"#\" + ui.draggable.attr('id') ).attr(\"key\");
							var lp = $(\"#\" + ui.draggable.attr('id') ).attr(\"lp\");
							
							$(\"#\" + ui.draggable.attr('id') ).remove();
							var res = autenticarHost( password , $(this).attr('servicio') , lp );
	
							if (res == 0 ){
								$(this).animate({
									height: 40,
									width: 40,
									opacity: 1
								},\"slow\").effect(\"bounce\", options, 500);
							}else{
								$(this).css(\"z-index\",\"999999\");
								$(this).animate({
									height: 40,
									width: 40,
									opacity: 1
								},\"slow\").effect(\"drop\", options, 500);

							}
						},
						over: function(event, ui) {
							$(this).animate({
								height: 50,
								width: 50
							},\"slow\");
						},
						out: function(event, ui) {
							$(this).animate({
								height: 40,
								width: 40
							},\"slow\");
						}
					});
			});
		});
	</script>";
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>