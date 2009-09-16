<?php
include "../gestp/includes/funciones.php"
?>
<div class='logoemsa'></div>
<div class='titulo'>Bienvenidos a la web de Especialidades Metalicas</div>
<div class='contenido' style='width: 500px;'>CD-Text es una extensión de
las especificaciones del Disco Compacto de Audio recogidas en el Libro
Rojo. Permite el almacenamiento de información adicional (por ejemplo:
nombre del álbum, título de la canción y artista) sobre un CD de audio
estándar. La información se almacena en la pista de entrada del CD,
donde hay aproximadamente cinco kilobytes de espacio disponible, o en
los subcanales R a W en el disco, que pueden almacenar alrededor de 31
megabytes. Estas áreas no se usan por los CDs acordes al Libro Rojo. El
texto se almacena en un formato utilizable por el Sistema Interactivo de
Transmisión de Texto (ITTS en sus siglas en inglés). El ITTS también se
utiliza en la difusión de audio digital (DAB) o el MiniDisc. Las
especificaciones se publicaron en septiembre de 1996, bajo el amparo de
Sony. El soporte de CD-Text es común, pero no universal. Existen
utilidades para extraer la información contenida e</div>
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


