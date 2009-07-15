<?php
/**
 * Representa un comando
 */ 

class Comando{

	private $nombre;
	private $comando;
	
	public function Comando( $nombre , $comando ){
		$this->nombre = $nombre;
		$this->comando = $comando;
	}
	
	public function getCmd(){
		return $this->comando;
	}
	
	public function getNombre(){
		return $this->nombre;
	}
	
	public function setCmd( $cmd ){
		$this->comando = $cmd;
	}
	public function setNombre( $nombre ){
		$this->nombre = $nombre ;
	}
}
?>