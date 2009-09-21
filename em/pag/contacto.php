<script type="text/javascript">

	function enviar(){
		var nombre = $("#nombre").val();
		var empresa = $("#empresa").val();
		var mail= $("#mail").val();
		var asunto= $("#asunto").val();
		var mensaje= $("#mensaje").val();

		
		if ( ningundatoVacio( nombre, empresa , asunto , mensaje )){

			contenido =nombre + " de la empresa "+ empresa + " ha contactado con emsa desde la web, con el siguiente mensaje:<br>"+ asunto +", " + mensaje;
			
			$.ajax( {
				type :"POST",
				url :"pag/ecorreo.php",
				data :"de="+ mail +"&contenido="+contenido,
				success : function(codigo) {
					alert(codigo)
				}
			});
		}else{
			alert("Datos incorrectos.");
		}
		
	}

	function  ningundatoVacio(nombre, empresa , asunto , mensaje ){

		if ( nombre != "" && empresa != "" && asunto != "" && mensaje != "" ){
			return true;
		}else{
			return false;
		}

	}
	function validarEmail(valor) {
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test(valor)){
			return true;
		} else {
			return false;
		}
	}
	
</script>
<div class='contacto'>

Contacte con nosotros.

<div>
<input type="text" id="nombre">
Nombre
</div>
<div>
<input type="text" id="empresa">
Empresa
</div>
<div>
<input type="text" id="mail">
E-mail
</div>
<div>
<input type="text" id="asunto">
Asunto
</div>
<div>
<input type="text" id="mensaje">
Mensaje
</div>

<input type="button" value="Contacte" onclick="enviar()">

</div>
<div class='sobre' ></div>