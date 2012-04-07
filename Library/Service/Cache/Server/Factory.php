<?php

namespace Service\Cache;

class Server_Factory
{
	private static $aO = array(); 

	public static function create($name)
	{
		$name = strtolower($name);
		
		if (array_key_exists($name, self::$aO))
		{
		    return self::$aO[$name];
		}
		
		switch ($name)
		{
			default: //memcache
				$class = '\Service\Cache\Server_Memcache';
			break;


			case 'memcachedb':
				$class = '\Service\Cache\Server_MemcacheDB';
			break;
		}

		return self::$aO[$name] = new $class();
	}
}