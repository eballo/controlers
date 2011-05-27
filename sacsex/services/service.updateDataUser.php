<?php
	include_once '../includes/servicesHeaders.php';


	//Conexion base de datos
	$link=conectar('bdsintesi');
	$update = "";
	$update2 = "";
	
	if ( $_SESSION['login'] && $_SESSION['type'] == "admin" ){
		
		if (isset($_POST['dayLimit'])){
			$query="UPDATE user SET DAY_LIMIT=".$_POST['dayLimit']." WHERE ID=".$_POST['iduser'];
			$update=mysql_query($query,$link);
		}
		
		if (isset($_POST['limit'])){
			$query="UPDATE user SET MAX_LIMIT=".$_POST['limit']." WHERE ID=".$_POST['iduser'];
			$update2=mysql_query($query,$link);
		}
		echo "$query";
		
		echo "U=$update U2=$update2";
		
		if ( $update2 == 1 && $update == 1 ){
			echo "<0>";
		}else{
			echo "<1>";
		}
		
	}
?>