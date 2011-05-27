	function validarKeyEnter( e ){
		if (e.keyCode == 13){
			validarPass();
		}
	}
	
	function configuracion(){
		$("#searchFiles").fadeIn("slow");
		$("#buscaForm").fadeOut("slow");
	}
	function busqueda(){
		$("#searchFiles").fadeOut("slow");
		$("#buscaForm").fadeIn("slow");		
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
				    	errorSave();
				    }
	    	  },
	    	  error: errorSave
	    	});

	}
	
	function saveOk(id){
		$("#data"+ id).find("#imgSave").fadeOut("slow");
	}
	
	function errorSave(){
		alert("No se han podido actualizar los datos del usuario.");
	}