<?php 
//TODO Revisar el codigo. En google Chrom, en la zona de administrador, no detecta el return como para hacer el submit
//
//TODO Boton de cierre de sesion 
//			(ahora resulta un poco incomodo sobretodo para hacer pruebas y pasar de un tipo de usuario a otro)
//
//TODO Ajustar la parte de usuarios para que todo tenga un formato similar a la zona de administrador
//
//TODO Servicios de notificacion hacia la bd (install.php y desintal.php)
//             install.php recibe como parametros el usuario y la contraseña en md5 y
//					le asigna un 1 a la celda "INSTALAT" conforme se ha instalado la aplicacion
// 				uninstall.php recibe como parametros el usuario y la contraseña en md5 y 
//					le asigna un 0 conforme la aplicacion fue desinstalada.
//			No me queda claro que devuelve ninguna de las dos
//
//TODO Servicio de notificacion de backups:
//			bckpsnotify.php: Recibe como parametros de entrada  usuario, passwd, fichero, fecha i tamaño
//					Comprueba, por un lado que el fichero no exceda el tamaño permitido diario ni el total de 
//					espacio reservado al usuario.
//					Si todo es correcto, sube los datos a la tabla backups i devuelve un 0
//						Si no, devolverá un 1 si el usuario ha excedido el espacio
//								y un 2 CUANDO?
//						En cualquier caso (devuelva un 1 o un 2) se eliminara el BK del servidor
//
//TODO Servicio fstabLine.php
//			No se si ahora será necesario (supongo que si). Recibe usuario y contraseña
// 			y añade una linea a /etc/fstab con los datos de la unidad montada
//
//TODO script de backups 

?>