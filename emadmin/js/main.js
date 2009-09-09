function cambiarPagina( p ){
	
	$("#contenedorApp").fadeOut("slow",function(){
		
		$.ajax( {
			type :"POST",
			url :"pag/" + p,
			data :"",
			success : function(codigo) {
			$("#contenedorApp").empty();
			$("#contenedorApp").append(codigo);
			$("#contenedorApp").fadeIn("slow");
			}
		});
		
	});
	
}
