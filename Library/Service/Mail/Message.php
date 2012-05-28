<?php

namespace Service\Mail;

class Message
{
    private $subject;
    private $bodyHTML;
    private $bodyTEXT;
    private $type = 'html';
    private $attachements;

    public function __construct($subject = '', $body = '', $type = 'html')
    {
        $this->subject($subject);
        $this->body($type, $body);
        $this->type($type);
    }
    
    public function subject($subject = null)
    {
        if ($subject !== null)
        {
            $this->subject = $subject;
        }
        return APPLICATION_ENVIRONMENT == 'staging' ? '@STAGING | '. $this->subject : $this->subject;
    }
    
    public function body($type = null, $body = null)
    {
        if ($type === null)
        {
            $type = 'HTML';
        }
        $type = strtoupper($type);
        if ($body !== null)
        {
            $this->{"body{$type}"} = $body;
        }
        return $this->{"body{$type}"};
    }
    
    public function type($type = null)
    {
        if ($type !== null)
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
            throw new Message_Attachement_Exception();
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
            throw new Message_Attachement_Exception();
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

class Message_Exception extends \Exception {}
class Message_Attachement_Exception extends \Exception {}
