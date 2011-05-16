<?php
	include_once 'functions.php';
	
	$link=conectar('bdsintesi');
	$query="SELECT ID,NAME FROM user";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	if ($rows >0){
		echo "<table><tr><th>ID Usuario</th><th>Nombre de Usuario</th></tr>";
		while ($row=mysql_fetch_array($result)){
			printf("<tr><td>%d</td><td>%s</td><td><a href=\"bajas.php?id=%d\">Borrar</a></td></tr>",$row["ID"],$row["NAME"],$row["ID"]);
		}
		desconectar($link);
		echo "</table>";
	}else{
		echo "No hi ha cap usuari <br />";
	}
?>
