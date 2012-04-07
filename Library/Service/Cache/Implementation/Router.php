<?php

namespace Service\Cache;

class Implementation_Router extends Serialize
{
	private static function key()
	{
		static $key;
		if (!$key)
		{
		    $key = Server_Abstract::keyprefix() . '_ROUTER';
		}
		return $key;
	}

    public static function read()
    {
		return parent::get(self::key());
    }

    public static function write($value)
    {
		return parent::set(self::key(), $value, '60000');
    }
}