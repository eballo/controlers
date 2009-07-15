<?php 
/**
 * Representa un terminal unix
 */

class Terminal{

	private $conectado = false;
	private $host;
	private $usuario;
	private $password;
	
	public function Terminal( $host , $usuario , $password ){
		$this->host = $host;
		$this->usuario = $usuario;
		$this->password = $password;
	}

	public function comando($cmd){
		$numrand = rand(0,999);

$script="#!/usr/bin/expect -f

spawn ssh ".$this->usuario."@".$this->host." $cmd

expect \"password:\"
send \"".$this->password."\\r\"

expect eof


";
		
		$fscript=fopen("scripts/autologin".$numrand,"w");
		fwrite($fscript,$script);
		fclose($fscript);
		
		return `/usr/bin/expect scripts/autologin$numrand`;
	}

	public function validarServer(){
		
	$numrand = rand(0,999);
$script="#!/usr/bin/expect -f

spawn ssh ".$this->usuario."@".$this->host." 

expect \"(yes/no)?\"
send \"yes\\r\"

expect eof


";	
		$fscript=fopen("scripts/autologin".$numrand,"w");
		fwrite($fscript,$script);
		fclose($fscript);
		return `/usr/bin/expect scripts/autologin$numrand`;
	}
}

?>