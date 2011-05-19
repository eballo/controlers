<?php
	include_once './includes/headers.php';
	
	$idFile=$_GET['idFile'];
	
	$link=conectar('bdsintesi');
	
	$query="DELETE FROM filepath WHERE IDF=$idFile";
	$result=mysql_query($query,$link);
	if ($result==1){
		$miss="Filepath eliminado";
   		echo "<script type='text/javascript'>
			alert('$miss');
			document.location = 'search.php';
		</script>";
	}
?>