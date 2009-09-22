<script type="text/javascript">

	function mostrarNotEnviado(){
		$("#notEnviado").fadeIn("slow");
		setTimeout("ocultarNotEnviado()",5000);
	}
	function ocultarNotEnviado(){
		$("#notEnviado").fadeOut("slow");
	}
	function enviar(){
		var nombre = $("#nombre").val();
		var empresa = $("#empresa").val();
		var mail= $("#mail").val();
		var asunto= $("#asunto").val();
		var mensaje= $("#mensaje").val();

		$("#buttonEnviar").attr("disabled", true);
		
		if ( validarEmail(mail) && ningundatoVacio( nombre, empresa , asunto , mensaje )){

			contenido =nombre + " de la empresa "+ empresa + " ha contactado con emsa desde la web, con el siguiente mensaje:<br>"+ asunto +", " + mensaje;
			
			$.ajax( {
				type :"POST",
				url :"pag/ecorreo.php",
				data :"de="+ mail +"&contenido="+contenido,
				success : function(codigo) {
					if(parseInt(codigo) == 1){
						mostrarNotEnviado();
						$("#buttonEnviar").attr("disabled", false);
					}
				}
			});
		}else{
			alert("Datos incorrectos.");
			$("#buttonEnviar").attr("disabled", false);
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

		var filtro=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	    if (filtro.test(valor)){
			return true;
		} else {
			return false;
		}
	}
	
</script>
<div class='contacto'>Contacte con nosotros.

<div><input type="text" id="nombre"> Nombre</div>
<div><input type="text" id="empresa"> Empresa</div>
<div><input type="text" id="mail"> E-mail</div>
<div><input type="text" id="asunto"> Asunto</div>
<div><input type="text" id="mensaje"> Mensaje</div>

<input id='buttonEnviar' type="button" value="Contacte" onclick="enviar()"></div>
<div class='sobre'></div>
<div id='notEnviado' class='notificacion' style='position: absolute; top: 250px; right: 100px ; display: none;'>Mensaje enviado.</div>
