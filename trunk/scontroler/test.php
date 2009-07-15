<?php 
require_once 'class/class.Terminal.php';


$term = new Terminal("172.20.1.22" , "root" , "barcelona");
$res = $term->comando("touch /tmp/amapola1");
$res = $term->comando("touch /tmp/amapola2");
$res = $term->comando("touch /tmp/amapola3");
$res = $term->comando("touch /tmp/amapola4");
$res = $term->comando("touch /tmp/amapola5");

$term2 = new Terminal("localhost" , "sadiel" , "barcelona");
$res .= $term2->comando("beep");
$res .= $term2->comando("beep");
$res .= $term2->comando("beep");
$res .= $term2->comando("beep");
$res .= $term2->comando("beep");


echo $res;
?>