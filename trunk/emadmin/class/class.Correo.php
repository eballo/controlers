<?php
/**
 * Representa un correo de emsa
 */

class Correo{
	
	private $para;
	private $contenido;
	
	public function Correo( $para , $contenido ){
		$this->para = $para;
		$this->contenido = $contenido;
	}
	
	public function enviar(){
		$fecha = date("Y-m-d H:m:s");
		$db = new Dbs();
		$db->query("INSERT into correo VALUES ('','".$this->para."','".$this->contenido."','$fecha',0)");	
		$db->desconectar();
		return 1;
	}
}
?>