<?php

namespace Service\Cache;

class ObPageTotal4GuestOnly extends Serialize
{
    protected static function sid()
    {
        return Server_Abstract::keyprefix() ."_ANONIM_". GEO_LOCATION ."_". GEO_COUNTRY ."_". ((int) FROM_OFFICE) ."_ipad". ((int) IpadDummy::instance()->isNeed()) ."_". md5($_SERVER['REQUEST_URI']);
    }

    protected static function cachenoneed()
    {
        static $noneed;
        if (!isset($noneed))
        {
            if (/*FROM_OFFICE || */User::instance()->isLogged() || !Zend_Registry::get('config')->cache->guest->enabled)
            {
                return $noneed = true;
            }
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                return $noneed = true;
            }
            foreach (Zend_Registry::get('config')->cache->guest->not_cache_url->toArray() AS $urlnotcache)
            {
                if (strpos($_SERVER['REQUEST_URI'], $urlnotcache) === 0)
                {
                    return $noneed = true;
                }
            }
            return $noneed = false;
        }
        return $noneed;
    }

    public static function get($key = null)
    {
		if (self::cachenoneed())
		{
		    return null;
		}
		return parent::get(self::sid());
    }
    
    public static function start()
    {
		if (self::cachenoneed())
		{
		    return null;
		}
        ob_start();
    }

    public static function stop()
    {
		if (self::cachenoneed())
		{
		    return null;
		}

        $output = ob_get_contents();
        ob_end_flush();
        if (strlen($output) > 5)
        {
            self::set(self::sid(), $output, Zend_Registry::get('config')->cache->guest->ttl, TagCollection::get());
        }
    }
}