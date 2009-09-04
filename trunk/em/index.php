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
			 
			 $("#dontam").click(function(){
				 cambiarPagina( "dontam.php");
				 });
			 $("#prin").click(function(){
				 cambiarPagina( "main.php");
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
 	<span class='boton'>Productos</span>
 	<span class='boton'>Galeria</span>
 	 <span class='boton'>Contacto</span>
 	 <span id='dontam' class='boton'>Donde Estamos</span>
 </div>
<div id='contenedorPrincipal'>
</div>
</div>
 </div>
</body>
</html>