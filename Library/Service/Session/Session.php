<?php

namespace Service\Session;

class Session extends \Zend_Session
{
    public static function getVar($var)
    {
        return @$_SESSION[$var];
    }

    public static function start()
    {
        return parent::start(\Service\Config::get('session'));
    }
}
