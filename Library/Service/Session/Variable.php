<?php

namespace Service\Session;

class Variable extends \Zend_Session_Namespace
{
    protected static $vars = array();

    /**
     * @param $varname
     * @return Variable
     */
    public static function get($varname)
    {
        if (!array_key_exists($varname, static::$vars))
        {
            static::$vars[$varname] = new static($varname);
        }
        return static::$vars[$varname];
    }
    
    public function __set($name, $value)
    {
        $value = serialize($value);
        return parent::__set($name, $value);
    }

    public function & __get($name)
    {
        if ($valuev = parent::__get($name))
        {
            $valuev = unserialize($valuev);
        }
        return $valuev;
    }
}