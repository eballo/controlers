	function validarKeyEnter( e ){
		if (e.keyCode == 13){
			validarPass();
		}
	}
	function muestraForm(){
		$("#buscaForm").fadeIn("slow");
	}
	function ocultaForm(){
		$("#buscaForm").fadeOut("slow");
	}
	function configuracion(){
		$("#searchFiles").fadeIn("slow");
		//$("#buscaForm").fadeOut("slow");
	}
	function busqueda(){
		$("#searchFiles").fadeOut("slow");
		//$("#buscaForm").fadeIn("slow");		
	}