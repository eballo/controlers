	function validarKeyEnter( e ){
		if (e.keyCode == 13){
			validarPass();
		}
	}
	
	function configuracion(){
		
		$(".searchButtonOffClick:first").addClass("searchButtonOff");
		$(".searchButtonOffClick:first").removeClass("searchButtonOffClick");
		
		$(".purgaButtonClick:first").addClass("purgaButton");
		$(".purgaButtonClick:first").removeClass("purgaButtonClick");
		
		
		$(".searchButton:first").addClass("searchButtonClick");
		$(".searchButton:first").removeClass("searchButton");
		
		$("#searchFiles").fadeIn("slow");
		$("#buscaForm").fadeOut("slow");
		$("#purgaForm").fadeOut("slow");	
	}
	function busqueda(){
		$(".searchButtonClick:first").addClass("searchButton");
		$(".searchButtonClick:first").removeClass("searchButtonClick");
		
		$(".purgaButtonClick:first").addClass("purgaButton");
		$(".purgaButtonClick:first").removeClass("purgaButtonClick");
		
		$(".searchButtonOff:first").addClass("searchButtonOffClick");
		$(".searchButtonOff:first").removeClass("searchButtonOff");
		
		$("#searchFiles").fadeOut("slow");
		$("#buscaForm").fadeIn("slow");	
		$("#purgaForm").fadeOut("slow");	
	}
	
	function purga(){

		$(".searchButtonClick:first").addClass("searchButton");
		$(".searchButtonClick:first").removeClass("searchButtonClick");
		
		$(".purgaButton:first").addClass("purgaButtonClick");
		$(".purgaButton:first").removeClass("purgaButton");
		
		$(".searchButtonOffClick:first").addClass("searchButtonOff");
		$(".searchButtonOffClick:first").removeClass("searchButtonOffClick");
		
		$("#searchFiles").fadeOut("slow");
		$("#buscaForm").fadeOut("slow");	
		$("#purgaForm").fadeIn("slow");		
	}
	
	function marcarChange( id ){
		$("#data"+ id).find("#imgSave").fadeIn("slow");
	}
	
	function save(id){
		
		var limit = $("#data"+ id).find("input[name='limit']:first").val();
	    var dayLimit = $("#data"+ id).find("input[name='dayLimit']:first").val();
	    
	    $.ajax({
	    	  url: 'services/service.updateDataUser.php',
	    	  type: 'POST',
	    	  async: false,
	    	  data: 'limit='+limit+'&dayLimit='+dayLimit+'&iduser='+id,
	    	  success: function( res ){
	    			
	    		if (res==0){
				    	saveOk(id);
				    }else{
				    	errorSave(res);
				    }
	    	  },
	    	  error: errorSave
	    	});

	}
	
	function saveOk(id){
		$("#data"+ id).find("#imgSave").fadeOut("slow");
	}
	
	function errorSave(res){
		if(res==1){
			alert("Usuario no actualizado.\n\nLa cuota Diaria supera al espacio total permitido");			
		}else{
			alert("No se han podido actualizar los datos del usuario.");
		}

	}
	
	function descargarFichero( idfile ){
	document.location='download.php?idfile=' + idfile ;
	}