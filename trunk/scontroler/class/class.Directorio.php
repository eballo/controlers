<?php
/**
 * Representa un directorio
 **/

class Directorio{

	public $nombreDir;
	public $nombre;
	public $descripcion;
	public $icono;

	public function Directorio($nombreDir, $nombre , $descripcion , $icono ){
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->icono = $icono;
		$this->nombreDir = $nombreDir;
	}

	public function getNombre() { return $this->nombre; }
	public function getNombreDir() { return $this->nombreDir; }
	public function getDescripcion() { return $this->descripcion; }
	public function getIcono() { return $this->icono; }
	public function setNombre($x) { $this->nombre = $x; }
	public function setNombreDir($x) { $this->nombreDir = $x; }
	public function setDescripcion($x) { $this->descripcion = $x; }
	public function setIcono($x) { $this->icono = $x; }

}

?>