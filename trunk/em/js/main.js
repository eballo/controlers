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