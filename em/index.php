<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
		<!--[if lt IE 8]>
		<script type="text/javascript" >
			document.location="http://www.mozilla-europe.org/es/firefox/";
		</script>
		<![endif]-->

		<title>Web Especialidades Metálicas S.A. 2009</title>
		<link rel='stylesheet' type='text/css' href='css/main.css'></link>
		<script type="text/javascript" src='js/jq.js'></script>
		<script type="text/javascript" src='js/main.js'></script>
		<script type="text/javascript">
		var inter;

		$(function(){
			 cambiarPagina( "main.php");
			$(".boton").mouseover(function(){
				$(this).attr("class","botoni");
				});
			$(".boton").mouseout(function(){
				$(this).attr("class","boton");
				});

			 $("#productos").click(function(){
				 cargarAplicacion( );
				 clearInterval(inter);
				 });
			 $("#contacto").click(function(){
				 cambiarPagina( "contacto.php");
				 clearInterval(inter);
				 });
			 $("#dontam").click(function(){
				 cambiarPagina( "dontam.php");
				 clearInterval(inter);
				 });
			 $("#prin").click(function(){
				 cambiarPagina( "main.php");
				 clearInterval(inter);
				 });
			 $("#galeria").click(function(){
				 cambiarPagina( "galeria.php");
				 clearInterval(inter);
				 });
			 $("#ofertas").click(function(){
				 cambiarPagina( "ofertas.php");
				 clearInterval(inter);
				 });
			 $("#historia").click(function(){
				 cambiarPagina( "historia.php");
				 clearInterval(inter);
				 });
			});
		</script>
	</head>
<body>
<div class='main'>
 <div class='ban'><img src='img/ban.png'></img></div>
 <div class='menu'>
 	<span id='prin' class='boton'>Principal</span>
 	<span class='boton' id='historia'>Historia</span>
 	<span class='boton' id='productos'>Productos</span>
 	<span class='boton' id='galeria'>Galería</span>
 	 <span class='boton' id='contacto'>Contacto</span>
 	 <span id='dontam' class='boton'>Donde Estamos</span>
 	 <span id='ofertas' class='boton'>Ofertas</span>
 </div>
<div id='contenedorPrincipal'>
</div>
</div>
<div class='reflejo'>
 	<img src='img/reflejo.png' />
</div>
 <div class='piepa'>Emsa 2009 by [Job3] Copyright by EMSA 2009</div>
</body>
</html>