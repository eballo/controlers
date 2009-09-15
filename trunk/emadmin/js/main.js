function cambiarPagina( p ){
	
	$("#contenedorApp").fadeOut("slow",function(){
		$("#contenedorApp").empty();
		$("#contenedorApp").append("<div class='loading'><img src='img/load.gif'/></div>");
		$("#contenedorApp").fadeIn("slow",function(){
			$.ajax( {
				type :"POST",
				url :"pag/" + p,
				data :"",
				success : function(codigo) {
					$("#contenedorApp").fadeOut("slow",function(){
						$("#contenedorApp").empty();
						$("#contenedorApp").append(codigo);
						$("#contenedorApp").fadeIn("slow");
					});
				}
			});		
		});
	});
}
