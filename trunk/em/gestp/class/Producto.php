<?php
/**
 * Representa un producto
 **/

class Producto{

	private $idfab;
	private $idproduc;
	private $nombre;
	private $descripcion;
	private $img;
	private $tipo;

	public function getIdfab() { return $this->idfab; }
	public function getIdproduc() { return $this->idproduc; }
	public function getNombre() { return $this->nombre; }
	public function getDescripcion() { return $this->descripcion; }
	public function getImg() { return $this->img; }
	public function getTipo() { return $this->tipo; }
	public function setIdfab($x) { $this->idfab = $x; }
	public function setIdproduc($x) { $this->idproduc = $x; }
	public function setNombre($x) { $this->nombre = $x; }
	public function setDescripcion($x) { $this->descripcion = $x; }
	public function setImg($x) { $this->img = $x; }
	public function setTipo($x) { $this->tipo = $x; }
	
	
	
}
?>
