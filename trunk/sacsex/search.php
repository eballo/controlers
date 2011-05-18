<?php
	include_once 'includes/userAuthValidation.php';
	include_once './includes/headers.php';


	$id_user=$_SESSION['id'];
	$link=conectar('bdsintesi');
	$query="SELECT USER_ID,FILEPATH FROM filepath WHERE USER_ID=$id_user";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	
	if ($rows >0){
		echo "<table><tr><th>PATH FILE</th></tr>";
		while($row=mysql_fetch_array($result)){
			printf("<tr><td>%s</td></tr><a href=\"bajafile.php?id=%d\">Borrar</a></td></tr>  ",$row['FILEPATH'],"<br>");
		}
		echo "</table><br>";
	}
	desconectar($link);
	
	/*if ($rows >0){
		//echo "<table><tr><th>ID Usuario</th><th>PATH de fichero</th></tr>";
		//while ($row=mysql_fetch_array($result)){
			printf("<tr><td>%d</td><td>%s</td><td><a href=\"bajafile.php?id=%d\">Borrar</a></td></tr>",$row['USER_ID'],$row['FILE_PATH'],$row['USER_ID']);
		}
		echo "</table>";
	desconectar($link);*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Pagina de usuarios</title>
</head>
<body>
	<h3> Alta de Files: </h3>
	<form action='altafile.php' method='post'>
		File: <input type="text" name='filepath' />
		<input type="submit" value='valida' /><br />
		<input type='hidden' name='alta' value='true' />
	</form>
</body>
</html>