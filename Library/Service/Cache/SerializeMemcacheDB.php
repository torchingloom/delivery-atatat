<?php

namespace Service\Cache;

class SerializeMemcacheDB extends Serialize
{
    public static function get($key)
    {
		return self::server()->get($key);
    }

    public static function set($key, $val, $lifetime = null)
    {
		return self::server()->set($key, $val, $lifetime);
    }

    public static function exists($key)
    {
		return self::server()->exists($key);
    }

	public static function cacheSidByMethod($method, $params = array())
	{
		$key = substr($method, strpos($method, '::') + 2);
		if ($params = (array) $params)
		{
		    $key .= '_'. md5(join('', $params));
		}
	    return "SNOB_". (!empty($_SERVER['HTTP_HOST']) ? md5($_SERVER['HTTP_HOST']) .'_'  : '') . $key;
	}

	/**
	 * @static
	 * @return Server_Memcache
	 */
	private static function server()
	{
		return Server_Factory::create('memcache');
	}
}