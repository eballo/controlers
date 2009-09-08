<?php
/**
 * Clase mysql , clase para facilitar el trabajo con la bbdd.
 *
 * @category PhpClass
 *
 * @author iForumDevTeam - Dev : Adrian Torres
 *
 * @version 1.0
 *
 */
class Dbs {

	static public $idcat = 0;
	private $idconex;
	private $query;
	private $retorno;
	private $contectado = false;
	private $configuracion;

	/**
	 * DBS Constructor
	 *
	 */
	public function dbs(){
		$this->conectar();
	}
	/**
	 * DBS Conecta con la base de datos
	 *
	 */
	private function conectar(){
		
		$this->idconex = mysql_connect("mysql.telesofa.com","qav939","servidor");
		if (isset($this->idconex)){
			mysql_select_db("qav939",$this->idconex);
			$this->contectado = true ;
			$this->query("SET NAMES UTF8");
		}else{
			$this->contectado = false;
			Sistema::colocarMantenimiento();
		}

	}

	/**
	 * Lanza una consulta a la BBDD
	 *
	 * @param Query Sql
	 *
	 */
	public function query($query){
		if ( $this->contectado ){
			$this->retorno = mysql_query($query,$this->idconex);
		}
	}
	/**
	 * Calcula el numero de filas del resultado
	 *
	 * @return Numero de filas
	 */
	public function numFilas(){
		if ( isset($this->retorno) ){
			return( mysql_num_rows($this->retorno));
		}
	}
	/**
	 * Calcula el numero de columnas del resultado
	 *
	 * @return Numero de columnas
	 */
	public function numColumnas(){
		if ( isset($this->retorno) ){
			return( mysql_num_fields($this->retorno));
		}
	}
	/**
	 * Retorna el resultado de la consula sql entero
	 *
	 * @return Resultado en formato retorno SQL
	 */
	public function getResultado(){
		if ( isset($this->retorno) ){
			return( $this->retorno);
		}
	}
	/**
	 * Almacena el resultado de una consulta en un vetor y lo retorna moviendo el cursor a la siguiente linea
	 *
	 * @return Vector con el contenido de una linea
	 */
	public function getFila(){
		if ( isset($this->retorno) ){
			return( mysql_fetch_array($this->retorno));
		}
	}
	/**
	 * DBS Desconecta de la base de datos
	 *
	 */
	public function desconectar(){
		if ($this->contectado){
			mysql_close($this->idconex);
		}
	}


}
?>