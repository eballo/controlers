<?php
	/**
	 * properties.php
	 * 
	 * Fichero en donde se establecen las variables para el uso de los demás scripts
	 * $MYSQL_IP 		direccion IP para MySQL
	 * $MYSQL_USER  	nombre de usuario para MySQL
	 * $MYSQL_PASSWORD	password de usuario para MySQL
	 * 
	 * $IP_SERVER	dirección IP del servidor
	 * $USER_BKPS	nombre de usuario para backups
	 * $TMP_PATH	ruta de directorio temporal para la copia de backups
	 * $BKPS_PATH	ruta de directorio final para la copia de backups
	 */

	//$MYSQL_IP="localhost";
	$MYSQL_IP="172.20.1.96";
	$MYSQL_USER="root";
	$MYSQL_PASSWORD="sadiel";
	
	$IP_SERVER="172.20.1.96";
	$USER_BKPS="sacs";
	$TMP_PATH="/home/sacs/bkpsTmp";
	$BKPS_PATH="/var/sacsexBkps";
?>