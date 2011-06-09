<?php
include_once 'includes/headers.php';	
include_once "includes/functions.php";
	
	echo $GLOBALS['MYSQL_BDNAME'];
		$link=conectar($GLOBALS['MYSQL_BDNAME']);
		$purgarQ="SELECT VALOR,FREQ from purga WHERE USER_ID=4714215";//$id
		$res=mysql_query($purgarQ,$link);
		$purgarA=mysql_fetch_array($res);
		$valor=$purgarA[0];
		$freq=$purgarA[1];

		if ($valor!=0){
			// Proceso Purga
			$dias=$valor;
			switch ($freq) {
				case 0:
					$text='DAY';
					break;
				case 1:
					$text='MONTH';
					break;
				case 2:
					$text='YEAR';
				break;
			} 
			$dateQ=" AND TIMESTAMPDIFF($text,TIMEDATE,curdate()) >=". $dias;
			$query="SELECT * FROM backups WHERE USER_ID=4714215".$dateQ.";";
			echo $query."<br>";
			$res=mysql_query($query,$link);
			$path=$GLOBALS['BKPS_PATH']."/4714215";

			while ($row=mysql_fetch_array($res)){
				echo $row['ID']."-".$row['FILENAME']."<br>";
				echo $path."/".$row['FILENAME'];
				if(unlink($path."/".$row['FILENAME'])){
					$delQ="DELETE from backups WHERE USER_ID=4714215 AND ID=".$row['ID'];
					$delRes=mysql_query($delQ,$link);			
				}
			}
			//
		}
?>