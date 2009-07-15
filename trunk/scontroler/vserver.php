<?php 
require_once 'class/class.Terminal.php';

//
//$term = new Terminal("172.20.1.22" , "root" , "barcelona");
//$res = $term->validarServer();

$term2 = new Terminal("localhost" , "sadiel" , "barcelona");
$res = $term2->validarServer();

echo $res;
?>