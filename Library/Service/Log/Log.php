<?php

namespace Service\Log;

include_once 'Log/Writer/Writer.php';

class Log
{
    protected $what;
    protected static $units;

    protected function __construct($what)
    {
        $this->what = $what;
    }

    /**
     * @static
     * @param  $what
     * @return Log
     */
    public static function &instance($what)
    {
        static $o;
        if (empty($o[$what]))
        {
            $o[$what] = new self($what);
        }
        return $o[$what];
    }

    public function __destruct()
    {
        $this->write();
    }

    /**
     * @param  $ident
     * @return \Service\Log\Log_Unit
     */
    public function &unit($ident, $msg = null)
    {
        $class = '\Service\Log\Log_Unit_Imp_'. $this->what;
        $unit = new $class($ident, $msg);
        self::$units[] = &$unit;
        return $unit;
    }

    protected function write()
    {
        if (self::isEnabled() && !LOGIT)
        {
            Log_Writer::write(self::cfg()->place, self::$units);
        }

        if (LOGIT)
        {
            Log_Writer::write('browser', self::$units);
        }
    }

    public static function isEnabled()
    {
        static $w;

        if (!$w)
        {
            $w = self::cfg()->write;

            if (LOGIT)
            {
                $w = true;
            }

            if (!empty($_SERVER['REQUEST_URI']))
            {
                $_ = strpos($_SERVER['REQUEST_URI'], '?');
                $requestpath = substr($_SERVER['REQUEST_URI'], 0, $_ !== false ? $_ : strlen($_SERVER['REQUEST_URI']));
                foreach (self::cfg()->notlog->url AS $notlog)
                {
                    if ($notlog == $requestpath)
                    {
                        $w = false;
                        break;
                    }
                }
            }
        }
        
        return $w;
    }

    /**
     * @static
     * @return Zend_Config
     */
    public static function cfg()
    {
        static $cfg;
        if (!$cfg)
        {
            $cfg = \Service\Config::get('log');
        }
        return $cfg;
    }
}