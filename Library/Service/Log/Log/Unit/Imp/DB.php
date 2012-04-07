<?php

namespace Service\Log;

class Log_Unit_Imp_DB extends Log_Unit
{
    public function __construct($ident, $msg = null)
    {
        ob_start();
        print_r($msg);
        $msg = ob_get_contents();
        ob_end_clean();
        parent::__construct($ident, $msg);
    }
}