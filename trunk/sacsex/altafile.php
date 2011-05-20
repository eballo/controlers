<?php 
	include_once 'includes/headers.php';
	
	$id=$_SESSION['id'];
	$filepath=$_POST['filepath'];
	$alta=$_POST['alta'];
	echo $id;
	echo $filepath;
	echo $alta;

	if ($alta == true){
		$link=conectar('bdsintesi');
		$query="INSERT INTO filepath (FILEPATH,USER_ID) VALUES ('$filepath',$id)";
		$result=mysql_query($query,$link);
		desconectar($link);
	
		echo"<script type='text/javascript'>
			document.location = 'search.php';
		</script>";
	}

?>
