<div class="<?php echo "$styleHeader"; ?>">
	<div class="tituloHeader">
		Sacsex V.1.0
	</div>
	<div class="infoUser">
	<?php 
		echo "<span><b>Usuario:</b> ".$usuario."</span>";

		if (kastomegas($sizeOcup) >= 1024){
			printf("<span><b>Espacio ocupado:</b> %.2f GB</span>",megasToGiga($sizeOcup));
		}
		else{
			printf("<span><b>Espacio ocupado:</b> %.2f MB</span>",kastomegas($sizeOcup));
		}
		
		if (kastomegas($sizeRes) >= 1024){
			printf("<span><b>Espacio restante:</b> %.2f GB</span>",megasToGiga($sizeRes));
		}
		else{
			printf("<span><b>Espacio restante:</b> %.2f MB</span>",kastomegas($sizeRes));
		}
		
		if ( $msgError != "" ){
			echo "<span><b>$msgError</b></span>";
		}
	?>
	</div>
	<div class="cierreSesion">
		<input type="button" value="Cerrar Sesi&oacute;n" onclick="javascript: document.location='logout.php'"/>
	</div>
</div>