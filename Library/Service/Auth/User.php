<?php

namespace Service\Auth;

class User
{
    protected static $instance;

    protected function __construct()
    {
    }

    public static function &instance()
    {
        if (empty(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}