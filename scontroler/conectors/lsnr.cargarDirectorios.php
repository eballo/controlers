<?php
//////////////////////////////////////////////////
require_once '../includes/textResponseLsnr.php';///
//////////////////////////////////////////////////


$lnsr = new Listener("Cargar_Directorios","TEXT","POST", $_POST, "NOAUTH", $_SESSION );

if ( $lnsr->esValido()){

	$directoriosArray = Directorios::leer();
	if (count($directoriosArray) > 0){
		foreach ($directoriosArray as $dir ){

			$ret.="
		<div class='servicioMainDir'>
		<table>
			<tr>
				<td>
					<div>
						<table class='expand'>
							<tr>
								<td rowspan='2'><img id='estadoImg".$dir->getNombre()."' src='img/start.png' /></td>
								<td style='width:100%'><b id='nombreServicio'>".$dir->getNombre()."</b></td>
								<td rowspan='2'>
								<td>".$dir->getDescripcion()."</td>
							</tr>
							
						</table>
					</div>
				</td>
				<td>
					<div class='servicioDatos'>
					
					</div>
				</td>
			</tr>
		</table>
		
		</div>
		</div>";
		}
	}else{
		$ret.="Nungún directorio";
	}

	//codigo para transformar lo candados en dropables
//	$ret.="
//	<script type='text/javascript'>
//		$(function(){
//	 		var options = {};
//	
//			$(\".estadoSeguridad\").each(function(){
//				$(this).droppable({
//						drop: function(event, ui) {
//							$(\"#\" + ui.draggable.attr('id') ).effect(\"explode\", options, 500);
//							var password = $(\"#\" + ui.draggable.attr('id') ).attr(\"key\");
//							var lp = $(\"#\" + ui.draggable.attr('id') ).attr(\"lp\");
//							
//							$(\"#\" + ui.draggable.attr('id') ).remove();
//							var res = autenticarHost( password , $(this).attr('servicio') , lp );
//	
//							if (res == 0 ){
//								$(this).animate({
//									height: 40,
//									width: 40,
//									opacity: 1
//								},\"slow\").effect(\"bounce\", options, 500);
//							}else{
//								$(this).css(\"z-index\",\"999999\");
//								$(this).animate({
//									height: 40,
//									width: 40,
//									opacity: 1
//								},\"slow\").effect(\"drop\", options, 500);
//
//							}
//						},
//						over: function(event, ui) {
//							$(this).animate({
//								height: 50,
//								width: 50
//							},\"slow\");
//						},
//						out: function(event, ui) {
//							$(this).animate({
//								height: 40,
//								width: 40
//							},\"slow\");
//						}
//					});
//			});
//		});
//	</script>";
}
$lnsr->addCuerpo($ret);
echo $lnsr->response();

?>