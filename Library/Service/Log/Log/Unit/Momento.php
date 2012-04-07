<?php

namespace Service\Log;

class Log_Unit_Momento extends Log_Unit
{
    public function __construct($ident, $msg = null)
    {
        parent::__construct($ident, $msg);
        $this->checkout();
    }
}