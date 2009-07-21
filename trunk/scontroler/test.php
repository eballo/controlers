<?php 
require_once 'class/class.Terminal.php';


//$term = new Terminal("172.20.1.22" , "root" , "barcelona");
//$res = $term->comando("touch /tmp/amapola1");
//$res = $term->comando("touch /tmp/amapola2");
//$res = $term->comando("touch /tmp/amapola3");
//$res = $term->comando("touch /tmp/amapola4");
//$res = $term->comando("touch /tmp/amapola5");

$term2 = new Terminal("localhost" , "root" , "matrixxx");
$term2->conectar();
//$res .= $term2->comando("beep");
//$res .= $term2->comando("free | tail -2 | head -1 | tr -s ' ' | cut -f3 -d' '");
//$res .= $term2->comando("beep");
//$res .= $term2->comando("beep");
//$res .= $term2->comando("beep");
$res .= $term2->comando("/etc/inid.d/apache2 stop");

echo $res;
?>