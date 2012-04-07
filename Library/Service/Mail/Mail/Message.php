<?php

namespace Service\Mail;

class Mail_Message
{
    private $subject;
    private $body;
    private $type = 'html';
    private $attachements;

    public function __construct($subject = '', $body = '', $type = 'html')
    {
        $this->subject($subject);
        $this->body($body);
        $this->type($type);
    }
    
    public function subject($subject = '')
    {
        if ($subject)
        {
            $this->subject = $subject;
        }
        return APPLICATION_ENVIRONMENT == 'staging' ? '@STAGING | '. $this->subject : $this->subject;
    }
    
    public function body($body = '')
    {
        if ($body)
        {
            $this->body = $body;
        }
        return $this->body;
    }
    
    public function type($type = '')
    {
        if ($type)
        {
            $this->type = $type;
        }
        return $this->type;
    }
	
    public function attachement(array $aData = null)
    {
        if ($aData)
        {
            $this->attachements[] = new Mail_Message_Attachement($aData);
        }
        return $this->attachements;
    }
}

class Mail_Message_Attachement
{
    public $filename;
    public $path;
    public $encoding = 'base64';
    public $type = 'application/octet-stream';

    private $storedir = '/tmp/';

    /**
     * @param array $aData
     *  need keys
     *      path or source
     *      filename
     *  maby keys
     *      encoding
     *      type
     *
     * @return array
     */
    public function __construct(array $aData)
    {
        if (empty($aData['filename']))
        {
            throw new Mail_Message_Attachement_Exception();
        }

        $this->filename = $aData['filename'];

        if (!empty($aData['path']))
        {
            $this->path = $aData['path'];
        }
        elseif (!empty($aData['source']))
        {
            file_put_contents($this->path = $this->storedir . md5(microtime(true)) . $this->filename, $aData['source']);
        }
        else
        {
            throw new Mail_Message_Attachement_Exception();
        }

        if (!empty($aData['encoding']))
        {
            $this->encoding = $aData['encoding'];
        }

        if (!empty($aData['type']))
        {
            $this->type = $aData['type'];
        }
    }
}

class Mail_Message_Exception extends \Exception {}
class Mail_Message_Attachement_Exception extends \Exception {}
