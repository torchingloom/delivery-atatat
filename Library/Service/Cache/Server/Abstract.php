<?php

namespace Service\Cache;

abstract class Server_Abstract
{
    protected static function cachenoneed()
    {
		return false
            || !\Service\Config::get('cache/enable');
		;
    }

	/**
	 * @static
	 * @param mixed $lifetime in seconds (if prefix "r:" $lifetime = $lifetime + rand(1, $lifetime))
	 * @return int|string
	 */
	protected static function lifetime($lifetime)
	{
		if ($lifetime == (string) (int) $lifetime)
		{
			return (int) $lifetime;
		}
		switch (substr($lifetime, 0, $seppos = strpos($lifetime, ':')))
		{
			case 'rand': // random
				$lifetime = substr($lifetime, $seppos + 1);
				return $lifetime + rand(1, $lifetime);
			break;
		}
		return (int) $lifetime;
	}

    protected static function logit($what, $msg)
    {
        return \Service\Log\Log::instance('Cache')->unit($what, $msg);
    }

    public static function keyprefix()
    {
        static $srv;
        if (!isset($srv))
        {
            $srv = self::keyprefixWithout();
            if (defined('SITE_REGION') && SITE_REGION)
            {
                $srv .= "_r". SITE_REGION ."_";
            }
        }
        return $srv;
    }

    public static function keyprefixWithout()
    {
        static $srv;
        if (!isset($srv))
        {
            if (APPLICATION_ENVIRONMENT != 'farm')
            {
                $srv = !empty($_SERVER['HTTP_HOST']) ? strtoupper(preg_replace('/[\.\-:]/is', '', $_SERVER['HTTP_HOST'])) : '';
            }
            else
            {
                $srv = "SNOB";
            }
        }
        return $srv;
    }
}