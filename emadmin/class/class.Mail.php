<?php
class Mail{

	private $para;
	private $asunto;
	private $contenido;
	private $errores;
	/**
	 * Constructor de mail
	 * @param $para
	 * @param $asunto
	 * @param $contenido
	 * @return unknown_type
	 */
	public function Mail( $para , $asunto , $contenido){
		$this->asunto = $asunto;
		$this->para = $para;
		$this->contenido = $contenido;
	}
	/**
	 * Envia un mail
	 * @return unknown_type
	 */
	public function enviar(){

		///////////////////Mail////////////////////////////////////////
		$MailSmtpHost="smtp.arrakis.es";
		$MailProtocol="25";
		$MailUser="ventas.emsa-es.com";
		$MailPassword="ventas";
		$MailFrom="ventas@emsa-es.com" ;
		$FromName="Ventas Emsa Web";
		/////////////////////////////// Config /////////////////////////////////////


		$mail1 = new phpmailer();
		$mail1->PluginDir = "../class/";

		$mail1->Host = $MailSmtpHost;
		$mail1->Port = 25;
		$mail1->CharSet = "UTF-8";
		$mail1->SMTPAuth = true;
		$mail1->Username = $MailUser;
		$mail1->Password = $MailPassword;
		$mail1->Timeout=30;
		$mail1->From = $MailFrom;
		$mail1->FromName = "$FromName";
		$mail1->Subject = "$this->asunto";
		$mail1->ClearAddresses();
		$mail1->AddAddress("$this->para");
		$mail1->Body = "$this->contenido";
		$mail1->AltBody = "Este correo contiene Html!!";
		$env_mail = $mail1->Send();
			
		$this->errores = $env_mail ." -- ".$mail1->ErrorInfo;


	}
	/**
	 * Retorna el buffer de error de phpMailer
	 * @return unknown_type
	 */
	public function getError(){
		return $this->errores;
	}
}
?>
