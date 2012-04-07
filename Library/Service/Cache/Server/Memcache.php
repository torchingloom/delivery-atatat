<?php

namespace Service\Cache;

class Server_Memcache
	extends Server_Abstract
	implements Server_Interface
{
    protected static
        $tagsKeys = array()
    ;
    public static function get($key)
	{
		if (self::cachenoneed())
		{
		    return null;
		}
        $result = self::obj()->load($key);
        self::logit(__METHOD__, ((int) (boolean) $result) .'  '. $key);
		return $result;
	}

    public static function set($key, $val, $lifetime = null, $tags = array())
	{
		if (self::cachenoneed())
		{
		    return null;
		}

        if ($tags)
        {
            $tags = array_unique((array) $tags);
            TagCollection::apped($tags);
        }

        $result = self::obj()->save($val, $key, $tags, self::lifetime($lifetime));

        self::logit(__METHOD__, ((int) $result) .'  '. $key . ($tags ? '	tags ('. count($tags) .'):'. join(', ', $tags): ''));
	}

    public static function exists($key)
	{
		if (self::cachenoneed())
		{
		    return null;
		}
        $result = (int) (bool) self::obj()->test($key);
        self::logit(__METHOD__, "{$result}  {$key}");
		return $result;
	}

    public static function clean($key)
    {
        $result = @ (int) (bool) self::remove($key);
        self::logit(__METHOD__, "{$result}  {$key}");
		return $result;
    }

    public static function cleanByTags($tags)
    {
        $tags = array_unique((array) $tags);
        $result = (int) self::obj()->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
        self::logit(__METHOD__, 'tags ('. count($tags) .'): '. join(', ', $tags));
        return $result;
    }

    private static function remove($key)
    {
        return (int) self::obj()->remove($key);
    }

    /**
     * @static
     * @return Zend_Backend_Interface
     */
	private static function &obj()
	{
		static $o;
		if (!$o)
		{
            $memcached = new \Zend_Cache_Backend_Memcached
            (
                array
                (
                    'servers' => array
					(
						array
						(
							'host' => \Service\Config::get('memcache/host'),
							'timeout' => 5,
							'retry_interval' => 5,
						)
					)
                )
            );
            $o = new \Service\Dklab_Cache_Backend_TagEmuWrapper($memcached);
		}
		return $o;
	}
}