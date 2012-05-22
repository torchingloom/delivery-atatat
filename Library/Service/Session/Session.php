<?php

namespace Service\Session;

class Session extends \Zend_Session
{
    public static function getVar($var)
    {
        return @$_SESSION[$var];
    }
}
