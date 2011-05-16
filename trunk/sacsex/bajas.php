<?php
   include_once ("functions.php"); 
    
   $link=conectar('bdsintesi');
   $id=$_GET['id']; 
   $query="delete from user where ID = $id";
   $x=mysql_query($query,$link);
   if ($x==1){
   		$miss="Usuari eliminat";
   		echo "<script type='text/javascript'>
			alert('$miss');
			document.location = 'administrador.php';
		</script>";
   }else{
   		$miss="Error al intentar Eliminar al usuari";
	   	echo "<script type='text/javascript'>
			alert('$miss');
			document.location = 'administrador.php';
		</script>";
   }
?>