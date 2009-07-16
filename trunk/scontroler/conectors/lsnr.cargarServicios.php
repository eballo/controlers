<?php
//////////////////////////////////////////////////
require_once '../includes/textResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Cargar_Servicios","TEXT","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$servicios = new Servicios();
	$serviciosArray = $servicios->getServicios();
	
	foreach ($serviciosArray as $serv ){
		
		$ret.="
		<div class='servicioMain' id='main".$serv->getNombre()."'>
		<table>
			<tr>
				<td>
					<div class='servicioInfo'  id='info".$serv->getNombre()."'>
						<table class='exapand'>
							<tr>
								<td rowspan='2'><img id='estadoImg".$serv->getNombre()."' src='img/start.png' /></td>
								<td><b id='nombreServicio'>".$serv->getNombre()."</b></td>
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
								<td rowspan='2'><img src='img/serv.png' /></td>
								<td><b>Host:</b> ".$serv->getHost()."</td>
								<td><b>Puerto:</b> ".$serv->getPuerto()."</td>
								<td rowspan='2'>
										<table id='opcionesServicio".$serv->getNombre()."'>
											<tr><td><div class='boton'>Parar</div></td></tr>
											<tr><td><div class='boton'>Reiniciar</div></td></tr>
										</table>
								</td>
								<td rowspan='2'>
										<table>
											<tr><td><div class='minBoton'>x</div></td></tr>
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
		<div class='desplegableComandos' id='desp".$serv->getNombre()."'>
			<div class='contenedorComandos' id='contenedorComandos".$serv->getNombre()."'>
				<div class='comando'>
					<table>
						<tr>
							<td>Limpiar logs</td>
							<td>rm -rf /var/logs</td>
							<td><img style='cursor:pointer;margin-left: 10px' src='img/run.png'></td>
						</tr>
					</table>
				</div>
				<div class='comandoi'>
					<table>
						<tr>
							<td>Limpiar logs</td>
							<td>rm -rf /var/logs</td>
							<td><img style='cursor:pointer;margin-left: 10px' src='img/run.png'></td>
						</tr>
					</table>
				</div>
				<div class='comando'>
					<table>
						<tr>
							<td>Limpiar logs</td>
							<td>rm -rf /var/logs</td>
							<td><img style='cursor:pointer;margin-left: 10px' src='img/run.png'></td>
						</tr>
					</table>
				</div>
		
		
		
			</div>
			<div  class='pieContenedorComandos'><img style='cursor:pointer' src='img/mas.png' onclick=addCmd('".$serv->getNombre()."') /> Add
			<input type='text' id='inputCmdNombre".$serv->getNombre()."' onclick=vaciari('inputCmdNombre".$serv->getNombre()."') value='< nombre >'/>
			<input type='text' id='inputCmd".$serv->getNombre()."' onclick=vaciari('inputCmd".$serv->getNombre()."') value=' < comando >'/>
			</div>
		</div>
		</div>";
	}

	
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>