<?php

namespace Service\Cache;

class Server_MemcacheDB
	extends Server_Abstract
	implements Server_Interface
{

	CONST RECONNECT_COUNT = 10;

	private static function &obj()
	{
		static $o;
		if (!$o)
		{
			$o = new \Memcache();
            $connected = false;
			for ($i = 0; $i < self::RECONNECT_COUNT; $i++)
			{
				if ($o->connect(self::cfgvar('host'), self::cfgvar('port'), 1))
				{
                    $connected = true;
				    break;
				}
				sleep(1);
			}
            if (!$connected)
            {
                $o = null;
            }
		}
		return $o;
	}

    public static function get($key)
	{
        if (self::obj())
        {
//            self::logit(__METHOD__, $key);
            return self::obj()->get($key);
        }
        return null;
	}

    public static function set($key, $val)
	{
        if (self::obj())
        {
//            self::logit(__METHOD__, $key);
            return self::obj()->set($key, $val);
        }
        return null;
	}

    public static function exists($key)
	{
        if (self::obj())
        {
            $result = (int) (bool) self::obj()->get($key);
//            self::logit(__METHOD__, "{$key} {$result}");
            return $result;
        }
        return null;
	}

    public static function clean($key)
	{
        if (self::obj())
        {
            $result = (int) (bool) self::obj()->delete($key);
//            self::logit(__METHOD__, "{$key} {$result}");
            return $result;
        }
        return null;
	}

    public static function cleanByTags($tags)
    {
        return null;
    }

	public static function &sessionKeys($lifetime = 10, $new_keys = true)
	{
		$keys = array();
		$lifetime *= 60;

		$db = dba_open(self::cfgvar('file'), 'r', 'db4');

		$key = dba_firstkey ($db);

		if ($new_keys)
		{
			while (false !== $key)
			{
				$data = self::obj()->get ($key);

				if ((time() - $data[0]) <= $lifetime)
				{
					$keys[] = $key;
				}

				$key = dba_nextkey ($db);
			}
		}
		else
		{
			while (false !== $key)
			{
				$data = self::obj()->get($key);

				if ((time() - $data[0]) > $lifetime)
				{
					$keys[] = $key;
				}

				$key = dba_nextkey ($db);
			}
		}

		dba_close ($db);
		return $keys;
	}

	public static function &sessionExpireKeys()
	{
		$lifetime = ini_get('session.gc_maxlifetime') / 60;
		return self::sessionKeys($lifetime, false);
	}

	// gc
	public static function gc()
	{
		$keys = self::sessionExpireKeys();
		$delcnt = 0;

		foreach ($keys as $key)
		{
			if (self::obj()->delete($key))
			{
				$delcnt++;
			}
		}

		return array('total' => count($keys), 'rm' => $delcnt);
	}

	/**
	 * Destructor - closes memcache connection
	 *
	 * @return     void
	 */
	public function __destruct()
	{
		self::obj()->close();
	}

	private static function cfgvar($varname)
	{
		if (!in_array($varname, array('host', 'port', 'file')))
		{
		    throw new Server_MemcacheDB_Exception_CFG("Bad var name '{$varname}'");
		}

		if ( !($value = \Service\Config::get("memcachedb/{$varname}")) )
		{
            throw new Server_MemcacheDB_Exception_CFG("Bad var value for '{$varname}'");
		}
        
		return $value;
	}
}



class Server_MemcacheDB_Exception_CFG extends \Exception
{
	
}