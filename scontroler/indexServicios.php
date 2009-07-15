<html>
<head>
<script type='text/javascript' src='js/jquery.js'></script>
<script type='text/javascript' src='js/jqueryui.js'></script>
<script type='text/javascript' src='js/main.js'></script>
<script type='text/javascript'>
//	$(function(){
//			cargarApp();
//	});
</script>
<link rel='stylesheet' type='text/css' href='css/main.css' />
</head>
<body>


<div class='mainFrame'>
<div id='zonaDeCarga'>

<div class='servicioMain' id='mainWebSphere'>
<table>
	<tr>
		<td>
			<div class='servicioInfo'  id='infoWebSphere'>
				<table class='exapand'>
					<tr>
						<td rowspan='2'><img src='img/start.png' /></td>
						<td><b>WebSphere</b></td>
					</tr>
					<tr>
						<td>Servidor aplicaciones bea de Integracion</td>
					</tr>
				</table>
			</div>
		</td>
		<td>
			<div class='servicioDatos'>
			<table class='expand'>
					<tr>
						<td rowspan='2'><img src='img/serv.png' /></td>
						<td><b>Host:</b> 127.0.0.1</td>
						<td><b>Puerto:</b> 8080 </td>
						<td rowspan='2'>
								<table>
									<tr><td><div class='boton'>Parar</div></td></tr>
									<tr><td><div class='boton'>Reiniciar</div></td></tr>
								</table>
						</td>
						<td rowspan='2'>
								<table>
									<tr><td><div class='minBoton'>x</div></td></tr>
									<tr><td><div class='minBoton' id='despcButonWebSphere' onclick=desplegarPanel('WebSphere')>+</div></td></tr>
								</table>
						</td>
					</tr>
					<tr>
						<td><b>Usuario:</b> job3</td>
						<td><b>Servicio:</b> webpshere1</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
<div class='desplegableComandos' id='despWebSphere'>
	<div class='contenedorComandos' id='contenedorComandosWebSphere'>
		<div class='comando'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>
		<div class='comandoi'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>
		<div class='comando'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>
				<div class='comandoi'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>
				<div class='comando'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>
				<div class='comandoi'>
			<table>
				<tr>
					<td>Limpiar logs</td>
					<td>rm -rf /var/logs</td>
					<td>Ejecutar</td>
				</tr>
			</table>
		</div>

	</div>
	<div  class='pieContenedorComandos'><img style='cursor:pointer' src='img/mas.png' onclick=addCmd('WebSphere') /> Add
	<input type='text' id='inputCmdNombreWebSphere' onclick=vaciari('inputCmdNombreWebSphere') value='< nombre >'/>
	<input type='text' id='inputCmdWebSphere' onclick=vaciari('inputCmdWebSphere') value=' < comando >'/>
	
	</div>
</div>
</div>



</div>
</div>


</body>
</html>
