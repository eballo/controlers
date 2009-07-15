<?php
/**
 * Comprueba los datos de un array , que no sean null ni tengan comillas
 */

class ValidarDatos{

	private $array;
	private $valido = true;

	public function ValidarDatos( $array){
		$this->array = $array;
	}
	/**
	 * Retorna si los datos son validos
	 * @return bool
	 */
	public function sonValidos(){
		return $this->valido;
	}

	public function validar(){

		$v = true;

		for ($i=0;$i< count($this->array);$i++){
			if ($this->array[$i] == ""){
				$v = false;
				break;
			}
			if (!$this->validaCaracteres($this->array[$i])) {
				$v = false;
				break;
			}
		}

		$this->valido = $v;
	}
	/**
	 * Valida que la cadena c no contenga caracteres raros
	 * @param string
	 * @return string
	 */
	private function validaCaracteres( $c ){
			
		$permitidos = "aáàbcdeéèfghiíìjklmnoóòpqrstuúùvwxyzñÑABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ &;";
		for ($i=0; $i<strlen($c); $i++){
			if (strpos($permitidos, substr($c,$i,1))===false){
				return false;
			}
		}
		return true;
	}
	/**
	 * Valida que el mail sea correcto
	 * @param $email
	 * @return bool
	 */
	public static function validarMail($email){
		$mail_correcto = 0;
		//compruebo unas cosas primeras
		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
			if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
				//miro si tiene caracter .
				if (substr_count($email,".")>= 1){
					//obtengo la terminacion del dominio
					$term_dom = substr(strrchr ($email, '.'),1);
					//compruebo que la terminación del dominio sea correcta
					if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
						//compruebo que lo de antes del dominio sea correcto
						$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
						$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
						if ($caracter_ult != "@" && $caracter_ult != "."){
							$mail_correcto = 1;
						}
					}
				}
			}
		}
		if ($mail_correcto){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Valida que el parametro sea un codigo postal
	 * @param $cp
	 * @return unknown_type
	 */
	public static function validarCodigoPostal( $cp ){

		if (strlen($cp) == 5){
			$permitidos = "0123456789";
			for ($i=0; $i<strlen($cp); $i++){
				if (strpos($permitidos, substr($cp,$i,1))===false){
					return false;
				}
			}
			return true;
		}else{
			return false;
		}

	}
	/**
	 * Valida una edad , correcta si mayor a 20 y menor a 120
	 * @param $edad
	 * @return unknown_type
	 */
	public static function validarEdad( $edad){
		if( $edad >= 20 && $edad <= 120 ){
			$permitidos = "0123456789";
			for ($i=0; $i<strlen($edad); $i++){
				if (strpos($permitidos, substr($edad,$i,1))===false){
					return false;
				}
			}
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Limpia un string preparandolo para utilizarlo en una consulta SQL
	 * @param $value
	 * @return unknown_type
	 */
	public static function limpiar($value){
		$value=str_replace("'","",$value);
		$value=str_replace(";","",$value);
		$value=str_replace("&","",$value);
		$value=str_replace("SELECT","",$value);
		$value=str_replace("DELETE","",$value);
		$value=str_replace("UPDATE","",$value);
		$value=str_replace("INSERT","",$value);

		$value = trim(htmlentities($value)); // Evita introducción código HTML

		if (get_magic_quotes_gpc()) $value = stripslashes($value);
		return $value;
	}

}
?>