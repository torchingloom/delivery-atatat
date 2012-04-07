<?php

namespace Service\Cache;

class Serialize implements CInterface
{
    public static function get($key = null)
    {
		return self::server()->get($key);
    }

    public static function set($key, $val, $lifetime = null, $tags = array())
    {
		return self::server()->set($key, $val, $lifetime, $tags);
    }

    public static function exists($key)
    {
		return self::server()->exists($key);
    }

    public static function clean($key)
    {
        return self::server()->clean($key);
    }

    public static function cleanByTags($tags)
    {
        return self::server()->cleanByTags($tags);
    }

	public static function cacheSidByMethod($method, $params = array())
	{
        static $addpars;
        if (!$addpars)
        {
            $addpars = array
            (
            );
        }
        return
            Server_Abstract::keyprefix()
            .'_'
            . str_replace('::', '_', $method)
            .'_'
            . md5(serialize(array_merge((array) $params, $addpars)))
        ;
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