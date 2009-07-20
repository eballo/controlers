<?php
/**
 * Representa un terminal unix
 */

class Terminal{

	private $sid;
	private $conectado = false;
	private $host;
	private $usuario;
	private $password;
	private $idcon;

	/**
	 * Genera un terminal por ssh
	 * @param $host
	 * @param $usuario
	 * @param $password
	 * @return unknown_type
	 */
	public function Terminal( $host , $usuario , $password ){
		$this->host = $host;
		$this->usuario = $usuario;
		$this->password = $password;
		$this->sid;

	}
	/**
	 * Conecta con el servidor
	 * @return unknown_type
	 */
	public function conectar(){

		if (!function_exists("ssh2_connect")) die(	$this->conectado = "No dispone de conector SSH");
		if(!($this->idcon = ssh2_connect($this->host, 22))){
			$this->conectado = "Error de conexions";
			die;
		} else {
			if(!ssh2_auth_password($this->idcon, $this->usuario, $this->password)) {
				$this->conectado = "Error autentificacion";
				die;
			} else {
				$this->conectado = true;
			}
		}
	
}

/**
 * Lanza un comando contra el servidor.
 * @param $cmd
 * @return unknown_type
 */
public function comando($cmd){


	if(!($stream = ssh2_exec($this->idcon, $cmd."; echo \"__COMMAND_FINALIZADO__\"" )) ){
		echo "fail: unable to execute command\n";
	} else{
		// collect returning data from command
		stream_set_blocking( $stream, true );
		$time_start = time();
		$data = "";
		while( true ){
			$data .= fread($stream, 4096);
			if(strpos($data,"__COMMAND_FINALIZADO__") !== false){
				break;
			}
			//				if( (time()-$time_start) > 5 ){
			//					break;
			//				}
		}
		fclose($stream);
	}


	return str_replace("__COMMAND_FINALIZADO__","",$data);
}
public function getHost() { return $this->host; }
public function getUsuario() { return $this->usuario; }
public function getPassword() { return $this->password; }
public function getIdcon() { return $this->idcon; }
public function setHost($x) { $this->host = $x; }
public function setUsuario($x) { $this->usuario = $x; }
public function setPassword($x) { $this->password = $x; }
public function setIdcon($x) { $this->idcon = $x; }
}

?>