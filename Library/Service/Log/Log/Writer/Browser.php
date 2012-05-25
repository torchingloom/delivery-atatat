<?php

namespace Service\Log;

class Log_Writer_Browser extends Log_Writer
{
    protected function put()
    {
        echo \Controller\ViewFuhrer::apply('_LOG_.phtml', array('log' => $this->log));
    }
}