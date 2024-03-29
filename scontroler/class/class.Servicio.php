<?php
/**
 * Esta clase representa un servicio
 *
 * @author Job3
 * @version 1.0
 **/

class Servicio {

	private $nombre;
	private $descripcion;
	private $host;
	private $puerto;
	private $user;
	private $password;
	private $nombreProceso;
	private $ficheroPid;
	private $cmdArranque;
	private $cmdParada;
	private $cmdReinicio;
	private $comandos;

	/**
	 * Constructor del servicio , si existe el servicio lo cargara automaticamente
	 * @param Nombre del servicio
	 * @return void
	 */
	public function Servicio( $servicio ){
		$this->nombre = $servicio;

		if (file_exists("servicios/".$_SESSION['directorio']."/".$this->nombre.".xml")){
			$this->cargarServicio();
		}
	}
	/**
	 * Guarda la configuración del servicio en el disco duro.
	 */
	public function guardar(){
		$data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
			<scontroler>
			    <host ip='".$this->host."'>
			        <usuario value='".$this->user."'/>
			        <password value='".$this->password."'/>
			    </host>
			    <servicio nombre='".$this->nombre."' nombreproceso='".$this->nombreProceso."'  puerto='".$this->puerto."'>
			        <datosexternos>
			            <pidfile value='".$this->ficheroPid."'/>
			        </datosexternos>
			        <commandos>
			        	<principales>
				            <inicio cmd='".$this->cmdArranque."'/>
				            <parada cmd='".$this->cmdParada."'/>
				            <reinicio cmd='".$this->cmdReinicio."'/>
			            </principales>
			            <otros>
			            	";
		if( count($this->comandos) > 0 ){
			foreach ($this->comandos as $comando){
				if ( $comando->getNombre() != "" && $comando->getCmd() != "" ){
					$data.="<comando nombre='".$comando->getNombre()."' cmd='".$comando->getCmd()."' />";
				}
			}
		}
		$data.="
			            </otros>
			        </commandos>
			        <descripcion>
			        $this->descripcion
			        </descripcion>
			    </servicio>
			</scontroler>
		";

			        $file = fopen("servicios/". $this->nombre.".xml" , "w" );
			        fwrite($file,$data);
			        fclose($file);

	}

	/**
	 * Elimina del disco duro la configuracion del servicio.
	 */
	public function eliminar(){
		unlink("servicios/".$_SESSION['directorio']."/".$this->nombre.".xml");
	}

	/**
	 * Arranca el servicio.
	 * @return pid.
	 */
	public function arrancar(){
		if ($_SESSION['hosts'][$this->nombre.$_SESSION['directorio']] ){
			$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre.$_SESSION['directorio']."password"]);
			$term->comando($this->cmdArranque);
		}
	}

	/**
	 * Para el servicio
	 * @param modo :: Puede ser forzado [ F ] o bien estandar [ S ]
	 */
	public function parar( $modo ){
		if ($_SESSION['hosts'][$this->nombre] ){
			$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre.$_SESSION['directorio']."password"]);
			if($modo == "S"){
				$term->comando($this->cmdParada);
			}else{
				$term->comando("kill -9 `cat ".$this->ficheroPid."`");
			}
		}
	}
	/**
	 * Reinicia el servicio
	 * @param modo :: Puede ser forzado [ F ] o bien estandar [ S ]
	 */
	public function reiniciar( $modo ){
		if ($_SESSION['hosts'][$this->nombre].$_SESSION['directorio'] ){
			$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre."password"] );
			if($modo == "S"){
				$term->comando($this->cmdReinicio);
			}else{
				$term->comando("kill -9 `cat ".$this->ficheroPid."`");
				$term->comando($this->cmdArranque);
			}
		}
	}

	/**
	 * Carga todos los datos de un servicio
	 */
	private function cargarServicio(){
		if (file_exists("servicios/".$_SESSION['directorio']."/".$this->nombre.".xml")) {
			$xml = simplexml_load_file("servicios/".$_SESSION['directorio']."/".$this->nombre.".xml");

			$this->nombreProceso = $xml->servicio['nombreproceso'];
			$this->puerto = $xml->servicio['puerto'];
			$this->host = $xml->host['ip'];
			$this->user = $xml->host->usuario['value'];
			$this->password = $xml->host->password['value'];
			$this->ficheroPid =$xml->servicio->datosexternos->pidfile['value'];
			$this->descripcion = $xml->servicio->descripcion;
			$this->cmdArranque =$xml->servicio->commandos->principales->inicio['cmd'];
			$this->cmdParada =$xml->servicio->commandos->principales->parada['cmd'];
			$this->cmdReinicio =$xml->servicio->commandos->principales->reinicio['cmd'];

			if (count($xml->servicio->commandos->otros->comando) > 0){
				foreach ($xml->servicio->commandos->otros->comando as $comando ){
					$this->comandos[count($this->comandos)] = new Comando($comando['nombre'] , $comando['cmd']);
				}
			}

		} else {
			exit('Failed to open test.xml.');
		}
	}
	/**
	 * Retorna el estado del servicio
	 * @return string
	 */
	public function estado(){
		$ress = `nmap $this->host -p $this->puerto | grep $this->puerto | tr -s " " | cut -f2 -d" " `;
		$res= substr($ress,0,4);

		if ( $res == "open"){
			return 0;
		}else{
			if ( $res == "clos"){
				return 1;
			}else{
				return 2;
			}
		}
	}
	/**
	 * Añade un comando par el servicio
	 * @param $nombre Nombre del comando
	 * @param $cmd Comando
	 */
	public function addCmd($nombre , $cmd ){

		$existe = false;
		if ($_SESSION['hosts'][$this->nombre] ){
			if (count($this->comandos) > 0){
				foreach ($this->comandos as $comando){
					if ($comando->getNombre() == $nombre ){//Primero miramos si existe
						$existe = true;
						$comando->setCmd($cmd);
						break;
					}
				}
			}
			if (! $existe ){ // Si no existia lo creamos nuevo
				$this->comandos[count($this->comandos)] = new Comando($nombre,$cmd);
			}
		}

	}

	/**
	 * Elimina el comando pasado como parametro
	 * @param $nombre Nombre del comando
	 */
	public function delCmd( $nombre){
		if ($_SESSION['hosts'][$this->nombre] ){
			if (count($this->comandos) > 0){
				foreach ($this->comandos as $comando){
					if ($comando->getNombre() == $nombre ){//Primero miramos si existe
						$comando->setNombre("");
						$comando->setCmd("");
						break;
					}
				}
			}
		}
	}
	/**
	 * Retorna el espacio libre de ram del host
	 * @return ram
	 */
	public function ramLibreHost(){
		$term = new Terminal($this->host,$this->user,$this->password);
		$term->conectar();
		$infoHost=$term->comando("free | tail -2 | head -1 | tr -s \" \" | cut -f4 -d\" \"");
		return $infoHost;
	}
	/**
	 * Retorna el espacio total ocupado de ram en el host
	 * @return ram_total
	 */
	public function ramOcupadaHost(){
		$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre.$_SESSION['directorio']."password"]);
		$term->conectar();
		$infoHost=$term->comando("free | tail -2 | head -1 | tr -s \" \" | cut -f3 -d\" \"");
		return $infoHost;

	}
	/**
	 * Retorna el tamaño total del disco
	 * @return tam_disco
	 */
	public function tamanoDiscoHost(){
		$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre.$_SESSION['directorio']."password"]);
		$term->conectar();
		$infoHost=$term->comando("df -h | grep \" /\"$ | tr -s \" \" | cut -f2 -d\" \"");
		return $infoHost;
	}
	/**
	 * Retorna el tamaño ocupado en disco
	 * @return ocu_disco
	 */
	public function estadoDiscoOcupado(){

		$term = new Terminal($this->host,$this->user,$_SESSION['hosts'][$this->nombre.$_SESSION['directorio']."password"]);
		$term->conectar();
		$infoHost=$term->comando("df -h | grep \" /\"$ | tr -s \" \" | cut -f5 -d\" \"");
		return $infoHost;
	}


	public function getNombre() {
		return $this->nombre;
	}
	public function getDescripcion() {
		return $this->descripcion;
	}
	public function getNombreProceso() {
		return $this->nombreProceso;
	}
	public function getFicheroPid() {
		return $this->ficheroPid;
	}
	public function getCmdArranque() {
		return $this->cmdArranque;
	}
	public function getCmdParada() {
		return $this->cmdParada;
	}
	public function getCmdReinicio() {
		return $this->cmdReinicio;
	}
	public function setNombre($x) {
		$this->nombre = $x;
	}
	public function setDescripcion($x) {
		$this->descripcion = $x;
	}
	public function setNombreProceso($x) {
		$this->nombreProceso = $x;
	}
	public function setFicheroPid($x) {
		$this->ficheroPid = $x;
	}
	public function setCmdArranque($x) {
		$this->cmdArranque = $x;
	}
	public function setCmdParada($x) {
		$this->cmdParada = $x;
	}
	public function setCmdReinicio($x) {
		$this->cmdReinicio = $x;
	}
	public function getHost() {
		return $this->host;
	}
	public function getUser() {
		return $this->user;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setHost($x) {
		$this->host = $x;
	}
	public function setUser($x) {
		$this->user = $x;
	}
	public function setPassword($x) {
		$this->password = $x;
	}
	public function setPuerto($x) {
		$this->puerto = $x;
	}
	public function getPuerto() {
		return $this->puerto ;
	}
	public function getComandos(){
		return $this->comandos;
	}
}

?>