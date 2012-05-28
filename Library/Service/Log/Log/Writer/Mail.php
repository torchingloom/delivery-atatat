<?php

namespace Service\Log;

include_once realpath(__DIR__ .'/../../../../Service/Mail/Mail/Mailer.php');
include_once realpath(__DIR__ .'/../../../../Service/Mail/Mail/Message.php');

class Log_Writer_Mail extends Log_Writer
{
    protected function put()
    {
        if (!$this->log)
        {
            return;
        }

        $oMail = new \Service\Mail\Mailer();
		$oMsg = new \Service\Mail\Message();
		$oMsg->type('html');
		$oMsg->subject($title = "LOG: ". date("H:i:s") ." {$_SERVER['HTTP_HOST']} {$_SERVER['REQUEST_URI']}");
		$oMsg->body('html', \Controller\ViewFuhrer::apply('tpl/_LOG_.phtml', array('log' => $this->log)));
		$oMail->send(Log::cfg()->email, $oMsg);
    }
}