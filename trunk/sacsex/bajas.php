<?php
   include_once ("includes/functions.php"); 
    
   $link=conectar('bdsintesi');
   $id=$_GET['id']; 
   $query="delete from user where ID = $id";
   $x=mysql_query($query,$link);
   
   if ($x==1){
   		$miss="Usuari eliminat";
   		echo "<script type='text/javascript'>
			alert('$miss');
			document.location = 'admin.php';
		</script>";
   }else{
   		$miss="Error al intentar Eliminar al usuari";
	   	echo "<script type='text/javascript'>
			alert('$miss');
			document.location = 'admin.php';
		</script>";
   }
?>