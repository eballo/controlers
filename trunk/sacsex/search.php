<?php
	include_once 'includes/userAuthValidation.php';
	include_once 'includes/headers.php';

	$id_user=$_SESSION['id'];
	$link=conectar('bdsintesi');
	$query="SELECT USER_ID,FILEPATH,IDF FROM filepath WHERE USER_ID=$id_user";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	echo "<h2> Modificacion de FilesPath </h2>";
	if ($rows >0){
		echo "<h3> Files: </h3>";
		echo "<table><tr>PATH FILE de Usuario:</tr>";
		while($row=mysql_fetch_array($result)){
			printf("<tr><td>%s</td><td><a href=\"bajafile.php?idFile=%d\">Borrar</a></td></tr>",$row['FILEPATH'],$row['IDF']);
		}
		echo "</table><br>";
	}
	desconectar($link);

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Alta de Files de Usuario</title>
</head>
<body>
	<h3> Alta de File: </h3>
	<form action='altafile.php' method='post'>
		File: <input type="text" name='filepath' />
		<input type="submit" value='valida' /><br />
		<input type='hidden' name='alta' value='true' />
	</form>
</body>
</html>