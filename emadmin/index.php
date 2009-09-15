<?php 
require_once 'seguridad/seguridad.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Administración Web EMSA 2009</title>
<link rel='stylesheet' type='text/css' href='css/main.css'></link>
<link rel='stylesheet' type='text/css' href='css/jquery.checkbox.css'></link>
<script type="text/javascript" src='js/jq.js'></script>
<script type="text/javascript" src='js/jquery.checkbox.min.js'></script>
<script type="text/javascript" src='js/main.js'></script>
<script type="text/javascript">

$(function(){

		$(".boton").mouseover(function(){
			$(this).attr("class","botoni");
			});
		$(".boton").mouseout(function(){
			$(this).attr("class","boton");
			});

		 $("#fabricantes").click(function(){
			 cambiarPagina("fabricantes.php");
			 });
		 $("#categorias").click(function(){
			 cambiarPagina("categorias.php");
			 });

		 $("#productos").click(function(){
			 cambiarPagina("productos.php");
			 });
		 $("#noticias").click(function(){
			 cambiarPagina("noticias.php");
			 });
		
		
});
</script>
</head>
<body>
<div class='menu'><span class='boton' id='fabricantes'>Fabricantes</span><span
	class='boton' id='categorias'> Categorias</span><span class='boton' id='productos'>
Productos</span><span class='boton' id='noticias'>
Noticias</span></div>
<div id='contenedorApp'></div>
</body>
</html>
<!--<span-->
<!--	class='boton'> Noticias</span>-->
