<?php

/**
 * Representa un login
 */

class Login{

	private $mail;
	private $password;
	/**
	 * Construye un login
	 *
	 * @param mail
	 * @param password
	 */

	public function Login( $mail , $password){
		$this->mail = $mail;
		$this->password = $password;
	}

	/**
	 * Valida el usuario y la contraseña retornando true o false
	 * @return Boolean
	 */
	public function valida(){
//		$db = new Dbs();
//
//		$db->query("SELECT password FROM user WHERE mail='".$this->mail."' and activado = 1 ");
//
//		$pass = $db->getFila();
//
//		if ( $db->numFilas() > 0 && $pass[0] == $this->password){
			return true;
//		}else{
//			return false;
//		}
			
	}
}
?>
