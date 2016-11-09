<?php

namespace Src\Logger;

use PHPMailer;

abstract class AbstractMailLogger
{

    protected $mailBody;

    abstract protected function setMailBody($message, $level);

    public function __construct()
    {
        $this->mailBody = false;
    }

    public function isMail()
    {
        return ($this->mailBody ? true : false);
    }

    public function send($emailFrom, $emailTo)
    {
        $host = trim(gethostname());
        $mail = new PHPMailer ();
        $mail->CharSet = "UTF-8";
        $mail->From = $emailFrom;
        $mail->FromName = $host;
        $mail->addAddress($emailTo);
        $mail->isHTML(true);
        $mail->Subject = 'Error -> ' . $host;
        $mail->Body = str_replace("\n", '<br />', $this->mailBody);
        $mail->AltBody = $this->mailBody;
        return ($mail->send() ? true : false);
    }
}

