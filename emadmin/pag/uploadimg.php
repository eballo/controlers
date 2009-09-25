<?php 
require_once '../seguridad/seguridad.php';

// Script Que copia el archivo temporal subido al servidor en un directorio.
echo '<p>Nombre Temporal: '.$_FILES['fileUpload']['tmp_name'].'</p>';
echo '<p>Nombre en el Server: '.$_FILES['fileUpload']['name'].'</p>';
echo '<p>Tipo de Archivo: '.$_FILES['fileUpload']['type'];


$tipo = substr($_FILES['fileUpload']['type'], 0, 5);
// Definimos Directorio donde se guarda el archivo
$dir = '../../em/gestp/img/data/';
// Intentamos Subir Archivo
// (1) Comprovamos que existe el nombre temporal del archivo
if (isset($_FILES['fileUpload']['tmp_name'])) {
// (2) - Comprovamos que se trata de un archivo de imágen
if ($tipo == 'image') {
// (3) Por ultimo se intenta copiar el archivo al servidor.

$file = rand(11111,99999).$_FILES['fileUpload']['name'];

$_SESSION['fname']=$file;
$_SESSION['ftemp']=$dir.$file;
$_SESSION['ferror']=false;

if (!copy($_FILES['fileUpload']['tmp_name'], $dir.$file))

echo chmod($dir.$file , 0744 );

$perms =  fileperms( $dir.$file );
if (($perms & 0xC000) == 0xC000) {
    // Socket
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    // Symbolic Link
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    // Regular
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    // Block special
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    // Directory
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    // Character special
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    // FIFO pipe
    $info = 'p';
} else {
    // Unknown
    $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
echo $info;

echo $_SESSION['ferror']=true;

}
else $_SESSION['ferror']=true;
}
else $_SESSION['ferror']=false; 

?>