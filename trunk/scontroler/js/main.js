var indexInfor = 11;
var indexDesple = 10;

function cargarApp() {
setInterval("validarEstadoServicios()",10000);

	$.ajax( {
		type :"POST",
		url :"conectors/lsnr.cargarServicios.php",
		data :"",
		success : function(serviciosHtml) {
			$("#zonaDeCarga").empty();
			$("#zonaDeCarga").append(serviciosHtml);
			validarEstadoServicios();
		}
	});
}

function desplegarPanel(id) {

	if ($("#desp" + id).css("height") == "1px") {

		ocultarPaneles();
		indexDesple = indexDesple + 5;
		indexInfor = indexInfor + 5;

		$("#desp" + id).css("z-index", indexDesple);
		$("#info" + id).css("z-index", indexInfor);

		$("#desp" + id).animate( {
			height :145
		}, "slow");

		$("#desp" + id).attr("estado", "desplegado");

		$("#despcButon" + id).empty();
		$("#despcButon" + id).append("-");

	} else {

		$("#desp" + id).animate( {
			height :1
		}, "slow");

		$("#desp" + id).attr("estado", "plegado");

		$("#despcButon" + id).empty();
		$("#despcButon" + id).append("+");

	}

}

function ocultarPaneles() {

	var id = $("div[estado='desplegado']:first").attr("id");

	$("#" + id).animate( {
		height :1
	}, "fast");

	$("#" + id).attr("estado", "plegado");

}

function addCmd(id) {

	var nombrecmd = $("#inputCmdNombre" + id).val();
	var comando = $("#inputCmd" + id).val();

	var utlimotipo = $("#contenedorComandos" + id).find("div:last").attr(
			"class");
	var tipo;

	if (utlimotipo == "comando") {
		tipo = "comandoi";
	} else {
		tipo = "comando";
	}

	if (nombrecmd != "" && comando != "") {
		var codigo = "<div class='"
				+ tipo
				+ "'><table><tr>"
				+ "<td><b> "
				+ nombrecmd
				+ "</b></td>"
				+ "<td>["
				+ comando
				+ "]</td>"
				+ "<td><img style='cursor:pointer;margin-left: 10px' src='img/run.png'></td></tr></table></div>";

		$("#contenedorComandos" + id).append(codigo);
	}

}

function vaciari(id) {
	$("#" + id).val("");
}

function validarEstadoServicios() {
	$(".servicioMain").each(
			function() {
				var servicio = $(this).find("#nombreServicio").text();

				$.ajax( {
					type :"POST",
					url :"conectors/lsnr.estadoServicio.php",
					data :"servicio=" + servicio,
					success : function(estado) {
						var codigoestado = $(estado).find("servicestatus:first").attr("code");

						switch (parseInt(codigoestado)) {
						case 0:
							$("#estadoImg" + servicio).attr("src",
									"img/start.png");
							$("#opcionesServicio" + servicio).empty();									
							$("#opcionesServicio" + servicio).append("<tr><td><div class='boton'>Parar</div></td></tr>"+
									"<tr><td><div class='boton'>Reiniciar</div></td></tr>");
							$("#despcButon" + servicio ).css("visibility","visible");
							break;
						case 1:
							$("#estadoImg" + servicio).attr("src",
									"img/stop.png");
							$("#opcionesServicio" + servicio).empty();									
							$("#opcionesServicio" + servicio).append("<tr><td><div class='boton'>Arrancar</div></td></tr>");
							$("#despcButon" + servicio ).css("visibility","hidden");
							break
						case 2:
							$("#estadoImg" + servicio).attr("src",
									"img/alert.png");
							$("#opcionesServicio" + servicio).empty();
							$("#despcButon" + servicio ).css("visibility","hidden");
							break
						}
					}
				});
			});
}

function mostrarInforHost(){
	cursorEspera();
	mostrarZonaInfor( "Test" , 100 );
	cursorNormal();
}

function mostrarZonaInfor( contenido , tamano ){
	$("#zonaInfor").animate({
		height: tamano
	},"slow",function(){
		$(this).find("#contenedorZonaInfor").fadeOut("slow",function(){
			$(this).empty();
			$(this).append(contenido);
			$(this).fadeIn("slow");
		});
	});
}
function ocultarZonaInfor(){
	$("#zonaInfor").animate({
		height: 0
	},"slow");
}

function cursorEspera(){
	$("document").css("cursor","wait");
}

function cursorNormal(){
	$("document").css("cursor","default");
}