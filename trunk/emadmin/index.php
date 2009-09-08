<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Administración Web EMSA 2009</title>
<link rel='stylesheet' type='text/css' href='css/main.css'></link>
<script type="text/javascript" src='js/jq.js'></script>
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

		});
		</script>
</head>
<body>
<div class='menu'><span class='boton' id='fabricantes'>Fabricantes</span><span
	class='boton'> Categorias</span><span class='boton'> Productos</span><span
	class='boton'> Noticias</span></div>
<div id='contenedorApp'>
</div>
</body>
</html>
