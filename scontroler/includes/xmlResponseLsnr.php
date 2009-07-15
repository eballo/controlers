<?php
session_start();
header('Content-Type: text/xml');

//Cargamos la conf
require_once '../conf/conf.php';

//Proceso de arranque
chdir( $APPHOME );

require_once 'class/class.Listener.php';
require_once 'class/class.Login.php';
require_once 'class/class.Servicio.php';
require_once 'class/class.Servicios.php';
require_once 'class/class.Comando.php';
require_once 'class/class.Terminal.php';
require_once 'class/class.ValidarDatos.php';



?>