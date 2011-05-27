<div class="headerMain">
	<div class="tituloHeader">
		Sacsex V.1.0
	</div>
	<div class="infoUser">
		<?php 
		echo "<span><b>Usuario:</b> ".$usuario."</span>";
		printf("<span><b>Espacio ocupado:</b> %.2f MB</span>",kastomegas($sizeOcup));
		printf("<span><b>Espacio restante:</b> %.2f MB</span>",kastomegas($sizeRes)); ?>
	</div>
	<div class="cierreSesion">
		<input type="button" value="Cerrar Sesion" onclick="javascript: document.location='logout.php'"/>
	</div>
</div>