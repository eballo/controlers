<html>
	<head>
		<title>Web Especialidades Metalicas S.A. 2008</title>
		<link rel='stylesheet' type='text/css' href='css/main.css'></link>
		<script type="text/javascript" src='js/jq.js'></script>
		<script type="text/javascript" src='js/main.js'></script>
		<script type="text/javascript">
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
				 });
			 $("#contacto").click(function(){
				 cambiarPagina( "contacto.php");
				 });
			 $("#dontam").click(function(){
				 cambiarPagina( "dontam.php");
				 });
			 $("#prin").click(function(){
				 cambiarPagina( "main.php");
				 });
			 $("#galeria").click(function(){
				 cambiarPagina( "galeria.php");
				 });
			});
		</script>
	</head>
<body>
<div class='main'>
 <div class='ban'><img src='img/ban.png'></img></div>
 <div class='menu'>
 	<span id='prin' class='boton'>Principal</span>
 	<span class='boton'>Historia</span>
 	<span class='boton' id='productos'>Productos</span>
 	<span class='boton' id='galeria'>Galeria</span>
 	 <span class='boton' id='contacto'>Contacto</span>
 	 <span id='dontam' class='boton'>Donde Estamos</span>
 </div>
<div id='contenedorPrincipal'>
</div>
</div>
<div class='reflejo'>
 	<img src='img/reflejo.png' />
</div>
 <div class='piepa'>Emsa 2009 by Adrian Torres [Job3] Copyright by Job3 Studios</div>
</body>
</html>