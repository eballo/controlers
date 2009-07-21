var indexInfor = 11;
var indexDesple = 10;
var actualizador ;
var numllave = 0;

function cargarApp() {
	actualizador = setInterval("validarEstadoServicios()",20000);

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

	if  ( hostAutenticado( id )){
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
			numllave
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

}

function ocultarPaneles()true {

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
							$("#opcionesServicio" + servicio).append("<tr><td><div class='boton' onclick=parar('" + servicio + "')>Parar</div></td></tr>"+
									"<tr><td><div class='boton' onclick=reiniciar('" + servicio + "')>Reiniciar</div></td></tr>");
							$("#despcButon" + servicio ).css("visibility","visible");
							break;
						case 1:
							$("#estadoImg" + servicio).attr("src",
									"img/stop.png");
							$("#opcionesServicio" + servicio).empty();									
							$("#opcionesServicio" + servicio).append("<tr><td><div class='boton' onclick=arrancar('" + servicio + "')>Arrancar</div></td></tr>");
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

function mostrarInforHost( servicio ){
	clearInterval(actualizador);
	cursorEspera();
	mostrarZonaInforHost( "<table><tr><td><div id='zonaDeCargaRamLibre'><img src='img/loading.gif' /></div></td>" +
			"<td><div id='zonaDeCargaRamOcupada'><img src='img/loading.gif' /></div></td>" +
			"<td><div id='zonaDeCargaDiscoTotal'><img src='img/loading.gif' /></div></td>" +
			"<td><div id='zonaDeCargaDiscoOcupado'><img src='img/loading.gif' /></div></td></tr></table>", 100 , servicio);

}

function mostrarRamLibre( servicio ){
	$.ajax( {
		type :"POST",
		url :"conectors/lsnr.estadoRamLibre.php",
		data :"servicio="+ servicio +"",
		success : function(infoHtml) {
			$("#zonaDeCargaRamLibre").fadeOut("slow",function(){
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrarRamOcupada( servicio ){
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.estadoRamOcupada.php",
		data :"servicio="+ servicio +"",
		success : function(infoHtml) {
			$("#zonaDeCargaRamOcupada").fadeOut("slow",function(){
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrartDiscoTotal(servicio ){
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.tamanoDisco.php",
		data :"servicio="+ servicio +"",
		success : function(infoHtml) {
			$("#zonaDeCargaDiscoTotal").fadeOut("slow",function(){
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrarDiscoOcupado(servicio ){
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.estadoDiscoOcupado.php",
		data :"servicio="+ servicio +"",
		success : function(infoHtml) {
			$("#zonaDeCargaDiscoOcupado").fadeOut("slow",function(){
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
	cursorNormal();
	actualizador = setInterval("validarEstadoServicios()",20000);
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

function mostrarZonaInforHost( contenido , tamano , servicio){
	$("#zonaInfor").animate({
		height: tamano
	},"slow",function(){
		$(this).find("#contenedorZonaInfor").fadeOut("slow",function(){
			$(this).empty();
			$(this).append(contenido);
			$(this).fadeIn("slow");
			
			mostrarRamOcupada(servicio);
			mostrarRamLibre(servicio);
			mostrartDiscoTotal(servicio);
			mostrarDiscoOcupado(servicio);
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


function generarLLave(){
	
	$(".llave:first").animate({
		top: 200,
		opacity : 0
	},"fast",function(){
		$(this).remove();
	});
	
	numllave++;
	var password = $.md5($("#inputkeygen").val());
	var key = "<img id='"+ numllave+"' key='"+password+"' src='img/key.png' class='llave'/>";
	$("#zonaCargaDeLlaves").append( key );
	$("#"+numllave).css("opacity","0");
	$("#"+numllave).animate({
		top: 100,
		opacity : 1
	},"fast",function(){
		$(this).effect("bounce",{},100,function(){
			$(this).draggable({
				revert: true
			});
		});
	});

}

function autenticarHost( password , servicio ){
	var retorno;
	
	$.ajax( {
		async: false,
		type :"POST",
		url :"conectors/lsnr.autenticarHost.php",
		data :"servicio="+ servicio +"&password="+ password +"",
		success : function(res) {
			if ( $(res).find("authhost:first").attr("result") == "ok" ){
				retorno = 1;
			}else{
				retorno = 0;
			}
		}
	});
	
	return retorno;
}

function hostAutenticado( servicio ){
	
	var retorno;
	
	$.ajax( {
		async: false,
		type :"POST",
		url :"conectors/lsnr.hostAutenticado.php",
		data :"servicio="+ servicio +"",
		success : function(res) {
			if ( $(res).find("authhost:first").attr("result") == "ok" ){
				retorno = 1;
			}else{
				retorno = 0;
				$("#estadoSeguridad" + servicio ).effect("bounce", {}, 500);
			}
		}
	});
	
	return retorno;
	
}

function arrancar( id ){
	var retorno;
	
	if  ( hostAutenticado( id )){
		$.ajax( {
			async: false,
			type :"POST",
			url :"conectors/lsnr.arrancarServicio.php",
			data :"servicio="+ id +"",
			success : function(res) {
	
			}
		});
	}
	
	return retorno;
}

function parar( id ){
	var retorno;
	
	if  ( hostAutenticado( id )){
		$.ajax( {
			async: false,
			type :"POST",
			url :"conectors/lsnr.pararServicio.php",
			data :"servicio="+ id +"",
			success : function(res) {
	
			}
		});
	}
	
	return retorno;
	
}
function reiniciar( id ){
	var retorno;
	
	if  ( hostAutenticado( id )){
		$.ajax( {
			async: false,
			type :"POST",
			url :"conectors/lsnr.reiniciarServicio.php",
			data :"servicio="+ id +"",
			success : function(res) {
	
			}
		});
	}
	
	return retorno;
	
}