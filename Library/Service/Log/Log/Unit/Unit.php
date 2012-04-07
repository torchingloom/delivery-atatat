<?php

namespace Service\Log;

class Log_Unit
{
    public
        $ident,
        $msg,
        $start,
        $stop,
        $time, // format number_format(..., 10)
        $checkouted = false
    ;
    public function __construct($ident, $msg = null)
    {
        $this->ident = $ident;
        $this->msg = $msg;
        $this->start = microtime(true);
    }

    public function checkout()
    {
        if ($this->checkouted)
        {
            throw new Log_Unit_Already_Filnalized_Exception;
        }
        $this->stop = microtime(true);
        $this->time = $this->stop - $this->start;
        $this->checkouted = true;
    }
}

class Log_Unit_Already_Filnalized_Exception extends \Exception
{
    
}