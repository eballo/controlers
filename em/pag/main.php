<?php
include "../gestp/includes/funciones.php"
?>
<div class='logoemsa'></div>
<div class='titulo'>Bienvenidos a la web de Especialidades Metalicas</div>
<div class='contenido' style='width: 500px;'>
Nuestras especialidad es solucionar, servir y asesorar
 los equipos tecnológicos más actuales y competitivos de las grandes marcas europeas y japonesas, 
 con espíritu joven de progreso, adaptándonos a los nuevos tiempos, en calidad, técnica, servicio, 
 precio y atención personalizada.</div>
<div class='img' style='position: absolute; right: 20px; top: 150px;'><img
	src='img/emsa/puertaprin.png' /></div>
<br>
<div class='titulo'>Noticias</div>

<?php 
	$conex_fab=Conectar();
	$datos=Lanzar_Consulta("SELECT titular , contenido , fecha from noticia",$conex_fab);	
	
	for ($i = 0 ; $i < $datos[1]; $i++){
		$noticia = mysql_fetch_array($datos[0]);
		
		echo "<div class='noticia' style='width: 500px;'><div class='reflejoNoticia'></div><div class='textoNoticia'><b>".$noticia[0]."</b><br>
".$noticia[1]."<br><b>".$noticia[2]."</b></div></div>";
		
	}
	Desconectar($conex_fab);
?>
<br><br>
<div class='tituloOfertas'>Lista de Productos en Oferta</div>

<div class='contenedorOfertas'><script type="text/javascript">

	var numpaginas = <?php echo Numero_Paginas_Oferta(); ?>; //Numero de paginaciones.
	var cursor = 1;

	//Cosas asociadas al control de las ofertas	
	function cargarOfertas(){
				var cantidad = cursor * 7;
				cursor++;
				
				$("#contenedorOfertas").fadeOut("slow",function(){
					$.ajax( {
						type :"POST",
						url :"pag/goferta.php",
						data :"p="+ cantidad,
						success : function(codigo) {
							$("#contenedorOfertas").empty();
							$("#contenedorOfertas").append(codigo);
							$("#contenedorOfertas").fadeIn("slow");
						}
					});
				});
			
				if (cursor > numpaginas){
					cursor = 0;
				}
			}
	
		$(function(){
			
			if (numpaginas >= 1){
				inter = setInterval( "cargarOfertas()" , 8000); 
			}

			 $("#contenedorOfertas").click(function(){
				 cambiarPagina( "ofertas.php");
				 clearInterval(inter);
				 });
		});
	</script>
<table id='contenedorOfertas' class='ofertas'>
	<tr id='contenedorOfertas'>
	<?php
	Cargar_ofertas_Ini();
	?>
	
	
	<tr>

</table>
</div>


