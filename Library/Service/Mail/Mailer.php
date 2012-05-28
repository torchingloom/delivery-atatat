<?php

namespace Service\Mail;

include_once 'PHPMailer/Core.php';

class Mailer
{
    /**
     * @var Mail_PHPMailer_Core
     */
    protected $oMail;
    protected $cfg = array();
    protected $lastob;

    public function __construct()
    {
        $this->mailerObjPrepare();
    }
    
    public function __call($method, $vars)
    {
        if (!method_exists($this->oMail, $method))
        {
            throw new \Service\Mail\Mailer_Exception();
        }
        return call_user_func_array(array($this->oMail, $method), $vars);
    }

    public function AddCC($emails)
    {
        if (!is_array($emails))
        {
            $emails = array($emails);
        }
        foreach ($emails AS $e)
        {
            $this->oMail->AddCC($e);
        }
    }

    public function AddBCC($emails)
    {
        if (!is_array($emails))
        {
            $emails = array($emails);
        }
        foreach ($emails AS $e)
        {
            $this->oMail->AddBCC($e);
        }
    }

    public function AddAddress($emails)
    {
        if (!is_array($emails))
        {
            $emails = array($emails);
        }
        foreach ($emails AS $e)
        {
            $this->oMail->AddAddress($e);
        }
    }

    public function SetFrom($address, $name = '', $auto=1)
    {
        return $this->oMail->SetFrom($address, $name, $auto);
    }

    public function send($emails, Message $oMsg)
    {
        ob_start();

        if (APPLICATION_ENVIRONMENT == 'staging')
        {
            $this->oMail->ClearAllRecipients();
			foreach ($this->getDeveloperEmail() AS $email)
			{
            	$this->oMail->AddAddress($email);
			}
        }
        else
        {
            if (!is_array($emails))
            {
                $emails = array($emails);
            }
            foreach ($emails AS $e)
            {
                $this->oMail->AddAddress($e);
            }
        }

        $this->oMail->Subject = $oMsg->subject();

		$this->oMail->IsHTML(false);
        $this->oMail->Body = $oMsg->body('text');
		if ($oMsg->type() == 'html')
		{
            $this->oMail->Body = $oMsg->body('html');
		    $this->oMail->IsHTML(true);
		}
		
        if ($ats = $oMsg->attachement())
        {
            foreach ($ats AS $a)
            {
                $this->oMail->AddAttachment($a->path, $a->filename, $a->encoding, $a->type);
            }
        }

        $result = $this->oMail->Send();

        $this->lastob = ob_get_contents();


        ob_end_clean();
        

        $this->oMail->ClearAddresses();

        return $result;
    }

    public function lastOb()
    {
        return $this->lastob;
    }

    protected function mailerObjPrepare()
    {
        $this->cfg = \Service\Config::get('mail');

//        echo "mail server {$this->cfg->smtp->host}:{$this->cfg->smtp->params->port}";

        $this->oMail = new Mail_PHPMailer_Core();
        $this->oMail->SMTPDebug = 3;
        $this->oMail->IsSMTP();
        $this->oMail->SMTPAuth = false;
        $this->oMail->Host = $this->cfg->smtp->host;
        $this->oMail->Port = $this->cfg->smtp->port;
        $this->SetFrom($this->cfg->smtp->fromEmail);
    }

    protected function getDeveloperEmail()
    {
        $mailData = new \Zend_Config_Xml(APPLICATION_PATH .'/configs/mail_config.xml');
        $mailData = $mailData->toArray();

        if (!empty($_SERVER['HTTP_HOST']))
        {
            foreach ($mailData AS $key => $data)
            {
                if (preg_match($data['domain'], $_SERVER['HTTP_HOST']))
                {
                    return explode(',', preg_replace($data['domain'], $data['address'], $_SERVER['HTTP_HOST']));
                }
            }
        }


        return array(substr($_SERVER['HOME'], strrpos($_SERVER['HOME'], '/') + 1) ."@jv.ru");
    }
}

class Mailer_Exception extends \Exception {}
