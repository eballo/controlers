<?php
	include_once '../includes/servicesHeaders.php';

	//Conexion base de datos
	$link=conectar($GLOBALS['MYSQL_BDNAME']);
	$update = "";
	$update2 = "";
	
	if ( $_SESSION['login'] && $_SESSION['type'] == "admin" ){
		if ($_POST['dayLimit']>$_POST['limit']){
			$update=2;
			$update2=2;
		}else{
			if (isset($_POST['dayLimit'])){
				$dayLimit=limpiar($_POST['dayLimit']);
				$query="UPDATE user SET DAY_LIMIT=".$dayLimit." WHERE ID=".$_POST['iduser'];
				$update=mysql_query($query,$link);
			}			
			if (isset($_POST['limit'])){				
				$limit=limpiar($_POST['limit']);
				$query="UPDATE user SET MAX_LIMIT=".$limit." WHERE ID=".$_POST['iduser'];
				$update2=mysql_query($query,$link);
			}
		}		
		if ( $update2 == 1 && $update == 1 ){
			echo "0";
		}elseif($update==2 && $update2==2){
			echo "1";
		}else{
			echo "2";
		}
		
	}
?>