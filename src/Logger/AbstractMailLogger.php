<?php

namespace Src\Logger;

use PHPMailer;

abstract class AbstractMailLogger {

	protected $mailBody; 
	abstract protected function setMailBody($message, $level);

	public function __construct() {
		$this->mailBody = false;
	}

	public function isMail() {
		return ( $this->mailBody ? true : false );
	}

	public function send($from, $to) {
		$host = trim ( gethostname () );
		$mail = new PHPMailer ();
		$mail->CharSet = "UTF-8";
		$mail->From = $from;
		$mail->FromName = 'DNS Resolver: ' . $host;
		$mail->addAddress ( $to );
		$mail->isHTML ( true );
		$mail->Subject = 'Error on DNS Resolver -> ' . $host;
		$mail->Body = str_replace ( "\n", '<br />', $this->mailBody );
		$mail->AltBody = $this->mailBody;
		return ( $mail->send() ? true : false );
		}
}

