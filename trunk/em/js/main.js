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
		$("#contenedorPrincipal").append("<iframe class='mainframe' src='gestp/index.php' /><div class='gestp'></div>");
		$("#contenedorPrincipal").fadeIn("slow");
	});
	
}