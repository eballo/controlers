<div class="headerMain">
	<div class="tituloHeader">
		Sacsex V.1.0
	</div>
	<div class="infoUser">
		<?php 
		echo "<span><b>Usuario:</b> ".$usuario."</span>
		<span><b>Espacio ocupado:</b> ".kastomegas($sizeOcup)." MB</span>
		<span><b>Espacio restante:</b> ".kastomegas($sizeRes)." MB</span>" ?>
	</div>
	<div class="cierreSesion">
		<input type="button" value="Cerrar Sesion" onclick="javascript: document.location='logout.php'"/>
	</div>
</div>