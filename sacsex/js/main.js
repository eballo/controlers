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