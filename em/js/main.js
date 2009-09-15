function cambiarPagina( p ){
	
	$("#contenedorPrincipal").fadeOut("slow",function(){
		
		$.ajax( {
			type :"POST",
			url :"pag/" + p,
			data :"",
			success : function(codigo) {
				$("#contenedorPrincipal").empty();
				$("#contenedorPrincipal").append(codigo);
				$("#contenedorPrincipal").fadeIn("slow");
			}
		});
		
	});
	
}


function cargarAplicacion(){
	
	
	
	$("#contenedorPrincipal").fadeOut("slow",function(){
		$("#contenedorPrincipal").empty();
		$("#contenedorPrincipal").append("<iframe class='mainframe' src='gestp/index.php' frameborder=0 scrolling=no /><div class='gestp'></div>");
		$("#contenedorPrincipal").fadeIn("slow");
	});
	
}
function cargarProducto( id ){

	$("#contenedorPrincipal").fadeOut("slow",function(){
		$("#contenedorPrincipal").empty();
		$("#contenedorPrincipal").append("<iframe class='mainframe' src='gestp/index.php?pdirecto="+ id + "' frameborder=0 scrolling=no /><div class='gestp'></div>");
		$("#contenedorPrincipal").fadeIn("slow");
	});
	
}