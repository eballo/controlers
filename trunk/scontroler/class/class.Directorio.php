<?php
/**
 * Representa un directorio
 **/

class Directorio{

	public $nombre;
	public $descripcion;
	public $icono;

	public function Directorio( $nombre , $descripcion , $icono ){
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->icono = $icono;
	}

	public function getNombre() { return $this->nombre; }
	public function getDescripcion() { return $this->descripcion; }
	public function getIcono() { return $this->icono; }
	public function setNombre($x) { $this->nombre = $x; }
	public function setDescripcion($x) { $this->descripcion = $x; }
	public function setIcono($x) { $this->icono = $x; }

}

?>