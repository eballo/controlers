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

echo $_SESSION['ferror']=true;

}
else $_SESSION['ferror']=true;
}
else $_SESSION['ferror']=false; 

?>