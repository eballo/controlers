<?php
require_once 'class/class.Servicio.php';
require_once 'class/class.Comando.php';

$serv = new Servicio('apache3');

chdir("conectors");

echo "Puerto:".$serv->getPuerto()." - IP:" . $serv->getHost();

echo "Estado : ".$serv->estado()."<br>";

echo ">".$serv->getCmdArranque()."<br>";
echo ">".$serv->getCmdParada()."<br>";
echo ">".$serv->getCmdReinicio()."<br>";
echo ">".$serv->getDescripcion()."<br>";
echo ">".$serv->getUser()."<br>";
echo ">".$serv->getPassword()."<br>";
echo ">".$serv->getNombre()."<br>";
echo ">".$serv->getFicheroPid()."<br>";

echo "Apadiendo comandos....";
//$serv->addCmd("Limpiar","cls -l");
//
//$serv->addCmd("Apagar","shutdown -h now");
//
//$serv->addCmd("LimpiarLogs","rm -rf /var/logs");
//
//$serv->addCmd("Logs","mv /ers asdas");

$serv->delCmd("Apagar");

$serv->guardar();

?>