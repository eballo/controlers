<html>
<head>
<script type='text/javascript' src='js/jquery.js'></script>
<script type='text/javascript' src='js/jqueryui.js'></script>
<script type='text/javascript' src='js/jquery-md5.js'></script>
<script type='text/javascript' src='js/jquery-base64.js'></script>
<script type='text/javascript' src='js/main.js'></script>
<script type='text/javascript'>
	$(function(){
			cargarApp();
	});
</script>
<link rel='stylesheet' type='text/css' href='css/main.css' />
</head>
<body>


<div class='mainFrame'>
<div id='zonaDeCarga'>


</div>
</div>

<div id='zonaInfor' class='zonaInfor'><div class='cerrarZonaInfor' onclick='ocultarZonaInfor()'><img src='img/close.png'></img></div><div id='contenedorZonaInfor'></div></div>
<div class='contenedorGeneradorLLaves'>
	<div id='generadorDeLlaves' class='generadorDeLlaves' >
		<b>Generador de llaves.</b><br><br>
		<span class='boton' onclick='generarLLave()'>Generar</span>
		<input type='password' id='inputkeygen' onclick="vaciari('inputkeygen')" value='< password >'></input>
	</div>
	<div id='zonaCargaDeLlaves' class='zonaCargaDeLlaves'></div>
</div>
</body>
</html>
