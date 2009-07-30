<?php
//////////////////////////////////////////////////
require_once '../includes/textResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Cargar_Directorios","TEXT","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$directoriosArray = Directorios::leer();
	if (count($directoriosArray) > 0){
		foreach ($directoriosArray as $dir ){
			
			if ($dir->getIcono() == "ico.png"){
				$imagen = "img/serv.png";
			}else{
				$imagen = "servicios/".$dir->getNombreDir()."/".$dir->getIcono();
			}
			
			$ret.="
		<div class='servicioMainDir' onclick=cargarServicios(\"".base64_encode($dir->getNombreDir())."\") >

					<div>
						<table class='expand'>
							<tr>
								<td rowspan='2'><div class='imagenDirectorio' style=\"background-image:url(".$imagen.");\" ><img src='img/sombra.png' /></div></td>
								<td  ><b id='nombreServicio'>".$dir->getNombre()."</b></td>
								<td rowspan='2'>
								<td style='width:100%'>".$dir->getDescripcion()."</td>
							</tr>
							
						</table>
		
		</div>
		</div>";
		}
	}else{
		$ret.="Nungún directorio";
	}

	$ret.="
	<script type='text/javascript'>
		$(function(){
		
			$(\".servicioMainDir\").mouseover(function(){
				$(this).addClass('servicioMainDirOver').removeClass('servicioMainDir');
			});
			
			$(\".servicioMainDir\").mouseout(function(){
				$(this).addClass('servicioMainDir').removeClass('servicioMainDirOver');
			});


		});
	</script>";
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>