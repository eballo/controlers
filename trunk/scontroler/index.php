<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--[if lt IE 8]>
<script type="text/javascript" >
	document.location="http://www.mozilla-europe.org/es/firefox/";
</script>
<![endif]-->
<!--[if eq IE 8]>
<script type="text/javascript" >
	document.location="http://www.mozilla-europe.org/es/firefox/";
</script>
<![endif]-->
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
<link href='css/jui/jquery-ui.css' type='text/css' rel='stylesheet' />
</head>
<body>


<div class='mainFrame'>
<div class='tic'><img src='img/tic.png' /></div>
<div id='zonaDeCarga'></div>
</div>

<div id='zonaInfor' class='zonaInfor'>
<div class='cerrarZonaInfor' onclick='ocultarZonaInfor()'><img
	src='img/close.png'></img></div>
<div id='contenedorZonaInfor'></div>
</div>
<div class='contenedorGeneradorLLaves'>
<div id='generadorDeLlaves' class='generadorDeLlaves'><b>Generador de
llaves.</b><br>
<br>
<span class='boton' onclick='generarLLave()'>Generar</span> <input
	type='password' id='inputkeygen' onclick="vaciari('inputkeygen')"
	value='< password >'></input></div>
<div id='zonaCargaDeLlaves' class='zonaCargaDeLlaves'></div>
</div>
<div id="dialogEliminar" title="Esta seguro?" style='display:none'>
<p>Esta seguro que desea eliminar este servicio?.</p>
</div>
<div class='pie'>Licencia GPL - ContolerS Google Code - Autor : Job3_14 - Version 1.0</div>
</body>
</html>
