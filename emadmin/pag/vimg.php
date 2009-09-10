<?php 
require_once '../seguridad/seguridad.php';

if (is_file($_SESSION['ftemp'])){
	echo "../../em/gestp/img/data/".$_SESSION['fname'];
}else{
	if ($_SESSION['ferror']){
		echo "2";
	}else{
		echo "0";
	}
	
}

?>