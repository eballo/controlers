<?php
/**
 * Representa un fabricante
 * @author job3
 */

class Fabricante{

	private $id_fab;
	private $nombre;
	private $direccion;
	private $otros_datos;
	private $img;

	public function getId_fab() { return $this->id_fab; }
	public function getNombre() { return $this->nombre; }
	public function getDireccion() { return $this->direccion; }
	public function getOtros_datos() { return $this->otros_datos; }
	public function getImg() { return $this->img; }
	public function setId_fab($x) { $this->id_fab = $x; }
	public function setNombre($x) { $this->nombre = $x; }
	public function setDireccion($x) { $this->direccion = $x; }
	public function setOtros_datos($x) { $this->otros_datos = $x; }
	public function setImg($x) { $this->img = $x; }
	
	/**
	 * Constructor de fabricante
	 */
	public function Fabricante( $id_fab ){
		$this->id_fab = $id_fab;
	}

	/**
	 * Guardar Fabricante
	 */
	public function guardar(){
		$dbs = new Dbs();
		$dbs->query("INSERT into fabricante values ('' , '".$this->nombre."','".$this->direccion."','".$this->otros_datos."','".$this->img."')");
		$dbs->desconectar();
	}
}
?>

