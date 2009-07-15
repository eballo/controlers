var indexInfor = 11;
var indexDesple = 10;

function cargarApp() {
	$.ajax( {
		type :"POST",
		url :"conectors/lsnr.cargarServicios.php",
		data :"",
		success : function(serviciosHtml) {
			$("#zonaDeCarga").empty();
			$("#zonaDeCarga").append(serviciosHtml);

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


function addCmd( id ){

	
	var nombrecmd = $("#inputCmdNombre" + id ).val();
	var comando = $("#inputCmd" + id ).val();
	
	if ( nombrecmd != "" && comando != "" ){
		var codigo = "<div class='comando'><table><tr>"+
		"<td> "+ nombrecmd +"</td>" +
		"<td>" + comando + "</td>" +
		"<td>Ejecutar</td></tr></table></div>";
		
		$("#contenedorComandos"+id).append( codigo );
	}

}

function vaciari( id ){
	$("#" + id).val("");
}