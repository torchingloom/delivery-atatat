<?php

namespace Service;

class Config
{
    /**
     * @var Zend_Config
     */
    private static $cfg;

    /**
     * must call in bootstrap
     * 
     * @static
     * @param $arr
     * @return void
     */
    public static function init($arr)
    {
        self::$cfg = new \Zend_Config($arr, true);
    }

    public static function get($key)
    {
        $var = self::$cfg;
        foreach (preg_split('|[/.\|]|', $key) AS $k)
        {
            if (empty($var->{$k}))
            {
                return null;
            }
            $var = $var->{$k};
        }

        return $var;
    }
}