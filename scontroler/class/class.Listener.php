<?php
/**
 * Clase listener representa un sistema de escucha con respuesta HTTP en Xml o texto plano
 **/

class Listener {

	static public $version = "1.0";

	private $nombre;
	private $formatoRetorno;
	private $formatoEntrada;
	private $cuerpo;
	private $bufferEntrada;
	private $peticionValida = false;
	private $bufferSesion;
	private $estado = "OK";


	/**
	 * Contruye un listener
	 * @param nombre Nombre del listener
	 * @param formatoRetorno [ XML | TEXT ]
	 * @param formatoEntrada [ POST | GET | BOTH ]
	 *
	 */
	public function Listener( $nombre,$formatoRetorno,$formatoEntrada,$bufferEntrada,$seguridad ,$bufferSesion){
		$this->nombre = $nombre;
		$this->formatoRetorno = $formatoRetorno;
		$this->formatoEntrada = $formatoEntrada;
		$this->bufferEntrada = $bufferEntrada;
		$this->bufferSesion = $bufferSesion;



		if ( $seguridad == "AUTH" ){
			$this->bufferEntrada['mail'] = base64_decode($this->bufferEntrada['i']);
			$this->bufferEntrada['password'] = $this->bufferEntrada['v'];
			$lg = new Login($this->bufferEntrada['mail'],$this->bufferEntrada['password']);
			$this->peticionValida = $lg->valida(); //Mail y contraseña correctos
		}else{
			if ( $seguridad == "NOAUTH" ){
				$this->peticionValida = true;
			}else{

				if ( $bufferSesion['loged'] && $bufferSesion['mail'] != "" && $bufferSesion['password'] != "" ){
					$this->bufferEntrada['mail'] = $this->bufferSesion['mail'];
					$this->bufferEntrada['password'] = $this->bufferSesion['password'];
					$this->peticionValida = true;
				}else{
					$this->peticionValida = false;
				}
			}

		}
	}

	/**
	 * Retorna el dato solicitado del buffer de entrada por GET
	 * @param Index_Del_Dato por ej: nombre
	 * @return Data
	 */
	public function doGet( $index ){
		if ($this->formatoEntrada == "GET"){
			return ValidarDatos::limpiar($this->bufferEntrada[$index]);
		}
	}
	/**
	 * Retorna si la auth es correcta
	 * @return bool
	 */
	public function esValido(){
		return $this->peticionValida;
	}
	/**
	 * Retorna el dato solicitado del buffer de entrada por POST
	 * @param Index_Del_Dato por ej: nombre
	 * @return Data
	 */
	public function doPost( $index ){
		if ($this->formatoEntrada == "POST"){
			return ValidarDatos::limpiar($this->bufferEntrada[$index]);
		}
	}
	/**
	 * Retorna el numero de parametros en el buffer de entrada
	 * @return numParams
	 */
	public function numParams( ){
		return count($this->bufferEntrada);
	}
	/**
	 * Establece el estado como KO
	 */
	public function setEstadoError(){
		$this->estado = "KO";
	}
	/**
	 * Retorna el codigo de retorno de listener
	 * @return theCode
	 */
	public function response(){
		if ($this->peticionValida){
			if ($this->formatoRetorno == "XML"){
				$retCode.= "<?xml version='1.0' encoding='UTF-8' ?> ";
				$retCode.= "<response><header version='".Listener::$version."' type='lsnr'><name>".$this->nombre."</name><client host='".$_SERVER[REMOTE_ADDR]."' port='".$_SERVER[REMOTE_PORT]."' method='".$_SERVER[REQUEST_METHOD]."'></client><numparams>".$this->numParams()."</numparams></header><auth result='OK' />";
				$retCode.= "<body result='".$this->estado."'>";
				$retCode.= $this->cuerpo;
				$retCode.= "</body>";
				$retCode.= "</response>";
			}else if ($this->formatoRetorno == "TEXT"){
				$retCode.= $this->cuerpo;
			}
		}else{
			if ($this->formatoRetorno == "XML"){
				$retCode.= "<?xml version='1.0' encoding='iso-8859-1' ?> ";
				$retCode.= "<response><header version='".Listener::$version."' type='lsnr'>Error Auth con ".$this->bufferEntrada['mail']."</header><auth id='auth' result='ERROR' />";
				$retCode.= "</response>";
			}else if ($this->formatoRetorno == "TEXT"){
				$retCode.= "Error Auth";
			}
		}
		return $retCode;
	}
	/**
	 * Añade contenido al cuerpo
	 * @param Contenido
	 */
	public function addCuerpo($data){
		$this->cuerpo .= $data;
	}
}
?>