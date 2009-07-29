var indexInfor = 11;
var indexDesple = 10;
var actualizador;
var numllave = 0;

function cargarApp() {
	actualizador = setInterval("validarEstadoServicios()", 20000);

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

	if (hostAutenticado(id)) {
		if ($("#desp" + id).css("height") == "1px") {

			ocultarPaneles();
			indexDesple = indexDesple + 5;
			indexInfor = indexInfor + 5;

			$("#desp" + id).css("z-index", indexDesple);
			$("#info" + id).css("z-index", indexInfor);

			$("#desp" + id).animate( {
				height :145
			}, "fast");

			$("#desp" + id).attr("estado", "desplegado");
			numllave
			$("#despcButon" + id).empty();
			$("#despcButon" + id).append("-");

		} else {

			$("#desp" + id).animate( {
				height :1
			}, "fast");

			$("#desp" + id).attr("estado", "plegado");

			$("#despcButon" + id).empty();
			$("#despcButon" + id).append("+");

		}
	}

}

function ocultarPanel(id) {

	if (hostAutenticado(id)) {
		if ($("#desp" + id).css("height") == "145px") {

			$("#desp" + id).animate( {
				height :1
			}, "slow");

			$("#desp" + id).attr("estado", "plegado");

			$("#despcButon" + id).empty();
			$("#despcButon" + id).append("+");

		}
	}

}

function ocultarPaneles() {

	var id = $("div[estado='desplegado']:first").attr("id");
	var idorg = $("div[estado='desplegado']:first").attr("idorg");

	$("#" + id).animate( {
		height :1
	}, "fast");

	$("#despcButon" + idorg).empty();
	$("#despcButon" + idorg).append("+");
	$("#" + id).attr("estado", "plegado");

}

function addCmd(id) {

	var nombrecmd = $("#inputCmdNombre" + id).val();
	var comando = $("#inputCmd" + id).val();
	var numcmd = getNextNumCmd(id);

	if (!existeComando(id, nombrecmd)) {

		var utlimotipo = $("#contenedorComandos" + id).find("div[tipo=cmd]:last").attr("class");
	
		var tipo;

		if (utlimotipo == "comando") {
			tipo = "comandoi";
		} else {
			tipo = "comando";
		}

		if (nombrecmd != "" && comando != "" && nombrecmd != "< nombre >"
				&& comando != "< comando >") {
			$.ajax( {
						type :"POST",
						url :"conectors/lsnr.anadirCmd.php",
						data :"servicio=" + id + "&nombre=" + nombrecmd
								+ "&cmd=" + comando + "",
						success : function(infoHtml) {
							var codigo = "<div class='"
									+ tipo
									+ "' numcmd='"+numcmd+"' tipo='cmd'><table><tr>"+
									"<td><b id='nombreCmd'>"+nombrecmd+"</b></td>" +
									"<td>[<span>"+comando+"</span>]</td>"+
									"<td>"+
									"<img onclick=\"ejecutarCmd('"+id+"',"+numcmd+" );\" style='cursor:pointer;margin-left:10px' src='img/run.png'/>" +
									"<img src='img/papelera.png' style='cursor:pointer;' onclick=\"delCmd('"+id+"', "+numcmd+")\"></td>"+
									"<img id='icon"+id+numcmd+"' src='img/informe.png' style='cursor:pointer;display:none' onclick=\"mostrarInformeCmd('"+id+numcmd+"')\"><div id='"+id+numcmd+"' style='display:none' rescmd=''></div></td>"+
									"</tr></table></div>";
							$("#contenedorComandos" + id).append(codigo);
						}

					});
		}
	}
}

function delCmd(id, numcmd) {

	nombrecmd = getNombreCmd(id, numcmd);

	$("#contenedorComandos" + id ).find("div").each( function() {
		if (parseInt($(this).attr("numcmd")) == parseInt(numcmd)) {
			$.ajax( {
				type :"POST",
				url :"conectors/lsnr.eliminarCmd.php",
				data :"servicio=" + id + "&nombre=" + nombrecmd + "",
				success : function(infoHtml) {
					
				}
			});
			$(this).remove();
		}
		
	});

}

function vaciari(id) {
	$("#" + id).val("");
}

function validarEstadoServicios() {
	$(".servicioMain")
			.each(
					function() {
						var servicio = $(this).find("#nombreServicio").text();

						$
								.ajax( {
									type :"POST",
									url :"conectors/lsnr.estadoServicio.php",
									data :"servicio=" + servicio,
									success : function(estado) {
										var codigoestado = $(estado).find(
												"servicestatus:first").attr(
												"code");

										switch (parseInt(codigoestado)) {
										case 0:
											$("#estadoImg" + servicio).attr(
													"src", "img/start.png");
											$("#opcionesServicio" + servicio)
													.empty();
											$("#opcionesServicio" + servicio)
													.append(
															"<tr><td><div class='boton' onclick=parar('"
																	+ servicio
																	+ "')>Parar</div></td></tr>"
																	+ "<tr><td><div class='boton' onclick=reiniciar('"
																	+ servicio
																	+ "')>Reiniciar</div></td></tr>");
											$("#despcButon" + servicio).css(
													"visibility", "visible");
											break;
										case 1:
											$("#estadoImg" + servicio).attr(
													"src", "img/stop.png");
											$("#opcionesServicio" + servicio)
													.empty();
											$("#opcionesServicio" + servicio)
													.append(
															"<tr><td><div class='boton' onclick=arrancar('"
																	+ servicio
																	+ "')>Arrancar</div></td></tr>");
											$("#despcButon" + servicio).css(
													"visibility", "hidden");
											break
										case 2:
											$("#estadoImg" + servicio).attr(
													"src", "img/alert.png");
											$("#opcionesServicio" + servicio)
													.empty();
											$("#despcButon" + servicio).css(
													"visibility", "hidden");
											break
										}
									}
								});
					});
}

function mostrarInforHost(servicio) {
	clearInterval(actualizador);
	cursorEspera();
	mostrarZonaInforHost(
			"<table><tr><td><div id='zonaDeCargaRamLibre'><img src='img/loading.gif' /></div></td>"
					+ "<td><div id='zonaDeCargaRamOcupada'><img src='img/loading.gif' /></div></td>"
					+ "<td><div id='zonaDeCargaDiscoTotal'><img src='img/loading.gif' /></div></td>"
					+ "<td><div id='zonaDeCargaDiscoOcupado'><img src='img/loading.gif' /></div></td></tr></table>",
			100, servicio);

}

function mostrarRamLibre(servicio) {
	$.ajax( {
		type :"POST",
		url :"conectors/lsnr.estadoRamLibre.php",
		data :"servicio=" + servicio + "",
		success : function(infoHtml) {
			$("#zonaDeCargaRamLibre").fadeOut("slow", function() {
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrarRamOcupada(servicio) {
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.estadoRamOcupada.php",
		data :"servicio=" + servicio + "",
		success : function(infoHtml) {
			$("#zonaDeCargaRamOcupada").fadeOut("slow", function() {
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrartDiscoTotal(servicio) {
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.tamanoDisco.php",
		data :"servicio=" + servicio + "",
		success : function(infoHtml) {
			$("#zonaDeCargaDiscoTotal").fadeOut("slow", function() {
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
}
function mostrarDiscoOcupado(servicio) {
	$.ajax( {

		type :"POST",
		url :"conectors/lsnr.estadoDiscoOcupado.php",
		data :"servicio=" + servicio + "",
		success : function(infoHtml) {
			$("#zonaDeCargaDiscoOcupado").fadeOut("slow", function() {
				$(this).empty();
				$(this).append($(infoHtml).find("body:first").text());
				$(this).fadeIn("slow");
			});
		}
	});
	cursorNormal();
	actualizador = setInterval("validarEstadoServicios()", 20000);
}

function mostrarZonaInfor(contenido, tamano) {
	$("#zonaInfor").animate( {
		height :tamano
	}, "slow", function() {
		$(this).find("#contenedorZonaInfor").fadeOut("slow", function() {
			$(this).empty();
			$(this).append(contenido);
			$(this).fadeIn("slow");
		});
	});
}

function mostrarZonaInforHost(contenido, tamano, servicio) {
	$("#zonaInfor").animate( {
		height :tamano
	}, "slow", function() {
		$(this).find("#contenedorZonaInfor").fadeOut("slow", function() {
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
function ocultarZonaInfor() {
	$("#zonaInfor").animate( {
		height :0
	}, "slow");
}

function cursorEspera() {
	$("document").css("cursor", "wait");
}

function cursorNormal() {
	$("document").css("cursor", "default");
}

function generarLLave() {

	$(".llave:first").animate( {
		top :200,
		opacity :0
	}, "fast", function() {
		$(this).remove();
	});

	numllave++;
	var password = $.md5($("#inputkeygen").val());
	var lp = $.base64Encode($("#inputkeygen").val());
	var key = "<img id='" + numllave + "' key='" + password
			+ "' lp='" + lp + "' src='img/key.png' class='llave'/>";
	$("#zonaCargaDeLlaves").append(key);
	$("#" + numllave).css("opacity", "0");
	$("#" + numllave).animate( {
		top :100,
		opacity :1
	}, "fast", function() {
		$(this).effect("bounce", {}, 100, function() {
			$(this).draggable( {
				revert :true
			});
		});
	});

}

function autenticarHost(password, servicio , passwordl ) {

	passwordl = passwordl.replace(/=/g, '%3D');

	var retorno;
	mostrarRunningHost(servicio);
	$.ajax( {
		async :false,
		type :"POST",
		url :"conectors/lsnr.autenticarHost.php",
		data :"servicio=" + servicio + "&password=" + password + "&lp=" + passwordl +"&",
		success : function(res) {
			if ($(res).find("authhost:first").attr("result") == "ok") {
				retorno = 1;
			} else {
				retorno = 0;
			}
		}
	});
	pararRunningHost(servicio);
	return retorno;
}

function hostAutenticado(servicio) {

	var retorno;
	$.ajax( {
		async :false,
		type :"POST",
		url :"conectors/lsnr.hostAutenticado.php",
		data :"servicio=" + servicio + "",
		success : function(res) {
			if ($(res).find("authhost:first").attr("result") == "ok") {
				retorno = 1;
			} else {
				retorno = 0;
				$("#estadoSeguridad" + servicio).effect("bounce", {}, 500);
			}
		}
	});
	return retorno;

}

function arrancar(id) {
	var retorno;

	if (hostAutenticado(id)) {

		mostrarRunningHost(id);
		$.ajax( {
			async :false,
			type :"POST",
			url :"conectors/lsnr.arrancarServicio.php",
			data :"servicio=" + id + "",
			success : function(res) {

			}
		});
		pararRunningHost(id);
	}

	return retorno;
}

function parar(id) {
	var retorno;

	if (hostAutenticado(id)) {
		mostrarRunningHost(id);
		$.ajax( {
			async :false,
			type :"POST",
			url :"conectors/lsnr.pararServicio.php",
			data :"servicio=" + id + "",
			success : function(res) {
				ocultarPanel(id);
			}
		});
		pararRunningHost(id);
	}

	return retorno;

}
function reiniciar(id) {
	var retorno;

	if (hostAutenticado(id)) {
		mostrarRunningHost(id);
		$.ajax( {
			async :false,
			type :"POST",
			url :"conectors/lsnr.reiniciarServicio.php",
			data :"servicio=" + id + "",
			success : function(res) {

			}
		});
		pararRunningHost(id);

	}

	return retorno;

}

function mostrarRunningHost(servicio) {
	$("#serverRunning" + servicio).effect("pulsate", {}, 1000);
}

function pararRunningHost(servicio) {
	$("#serverRunning" + servicio).fadeOut("slow", function() {
		actualizarServicio(servicio);
	});

}

function actualizarServicio(servicio) {
	$.ajax( {
				async :false,
				type :"POST",
				url :"conectors/lsnr.estadoServicio.php",
				data :"servicio=" + servicio,
				success : function(estado) {
					var codigoestado = $(estado).find("servicestatus:first")
							.attr("code");

					switch (parseInt(codigoestado)) {
					case 0:
						$("#estadoImg" + servicio).attr("src", "img/start.png");
						$("#opcionesServicio" + servicio).empty();
						$("#opcionesServicio" + servicio)
								.append(
										"<tr><td><div class='boton' onclick=parar('"
												+ servicio
												+ "')>Parar</div></td></tr>"
												+ "<tr><td><div class='boton' onclick=reiniciar('"
												+ servicio
												+ "')>Reiniciar</div></td></tr>");
						$("#despcButon" + servicio)
								.css("visibility", "visible");
						break;
					case 1:
						$("#estadoImg" + servicio).attr("src", "img/stop.png");
						$("#opcionesServicio" + servicio).empty();
						$("#opcionesServicio" + servicio).append(
								"<tr><td><div class='boton' onclick=arrancar('"
										+ servicio
										+ "')>Arrancar</div></td></tr>");
						$("#despcButon" + servicio).css("visibility", "hidden");
						break
					case 2:
						$("#estadoImg" + servicio).attr("src", "img/alert.png");
						$("#opcionesServicio" + servicio).empty();
						$("#despcButon" + servicio).css("visibility", "hidden");
						break
					}
				}
			});
}

function existeComando(servicio, nombre) {
	var ret;
	$("#contenedorComandos" + servicio).find("div[tipo=cmd]").each( function() {
// alert("Nombre" + nombre +"==" +$(this).find("#nombreCmd").text() );
		if (nombre == $(this).find("#nombreCmd").text()) {
			ret = true;
		} else {
			ret = false;
		}
	});
	return ret;
}

function ejecutarCmd(servicio, numcmd) {
	var retorno;

	cmd = getCmd(servicio, numcmd);

	if (hostAutenticado(servicio)) {
		mostrarRunningHost(servicio);
		$.ajax( {
			async :false,
			type :"POST",
			url :"conectors/lsnr.ejecutarComando.php",
			data :"servicio=" + servicio + "&comando=" + cmd + "",
			success : function(res) {
				$("#"+servicio+numcmd).text($(res).find("comando").text());
				$("#icon"+servicio+numcmd).fadeIn("slow");
			}
		});
		pararRunningHost(servicio);
	}
	return retorno;
}

function getCmd(servicio, numcmd) {
	var ret;
	$("#contenedorComandos" + servicio).find("div").each( function() {

		if (parseInt($(this).attr("numcmd")) == parseInt(numcmd)) {
			ret = $(this).find("span:first").text();
		}

	});
	return ret;
}

function getNombreCmd(servicio, numcmd) {
	var ret;
	$("#contenedorComandos" + servicio).find("div").each( function() {

		if (parseInt($(this).attr("numcmd")) == parseInt(numcmd)) {
			ret = $(this).find("#nombreCmd").text();
		}

	});
	return ret;
}

function mostrarInformeCmd( id ){
	var rescmd=$("#"+id).text();
	if (rescmd != ""){
		codigo = "<div class='contenedorInformesComando'>" + rescmd +"</div>"
		mostrarZonaInfor(codigo,300);
	}else{
		codigo = "<div class='contenedorInformesComando'>Ningún dato.</div>"
		mostrarZonaInfor(codigo,300);
	}
}

function getNextNumCmd(id){
	var ret = $("#contenedorComandos" + id).find("div[tipo=cmd]:last").attr("numcmd");
	if ( ret >= 0 ){
		ret = parseInt(ret) +1;
	}else{
		ret = 0;
	}
	return ret;
}

function eliminarServicio(servicio ){
	
	if ( hostAutenticado(servicio) ){
		$("#dialogEliminar").dialog({
				resizable: false,
				minWidth: 300,
				minHeight: 50,
				buttons: {
					si:  function(){
							$(this).dialog('close');
							$(this).dialog('destroy');
							$("#main"+ servicio).fadeOut("slow");
						},
					no:  function(){
							$(this).dialog('close');
							$(this).dialog('destroy');
						}
					}
				});
	}
	
}