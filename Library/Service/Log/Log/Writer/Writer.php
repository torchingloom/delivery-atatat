<?php

namespace Service\Log;


include_once realpath(__DIR__ .'/../../../../Controller/ViewFuhrer.php');

class Log_Writer
{
    protected
        $units,
        $place,
        $log = array()
    ;

    private static $alreadywrite = false;

    public static function write($place, $units)
    {
        if (self::$alreadywrite && !(LOGIT && $place == 'browser'))
        {
            return;
        }
        
        $class = '\Service\Log\Log_Writer_FileSystem';
        switch ($place)
        {
            case 'php_log':
                $class = '\Service\Log\Log_Writer_PHPLog';
            break;
            case 'browser':
                $class = '\Service\Log\Log_Writer_Browser';
            break;
            case 'mail':
                $class = '\Service\Log\Log_Writer_Mail';
            break;
        }
        include_once substr($class, strrpos($class, '_') + 1) .'.php';
        $o = new $class($units);
        $o->put();
        self::$alreadywrite = 1;
    }

    public function __construct($units)
    {
        $this->units = $units;
        $this->build();
    }
    
    
    protected function build()
    {
        if (!$this->log)
        {
            $part = &$this->log;

            $part = array('DB' => array(), 'Cache' => array());

            foreach ($this->units AS $unit)
            {
                $partkey = str_replace('\Service\Log\Log_Unit_Imp_', '', get_class($unit));
                if (!array_key_exists($partkey, $part))
                {
                    $part[$partkey] = array('totaltime' => 0, 'totalcount' => 0, 'units' => array());
                }

                switch ($partkey)
                {
                    default:
                        $ident = $unit->ident;
                    break;

                    case 'Cache':
                        $ident = str_replace('Cache_Server_', '', $unit->ident);
                    break;
                }

                @$part[$partkey]['totaltime'] += $unit->time;
                @$part[$partkey]['totalcount']++;
                @$part[$partkey]['units'][$ident]['totaltime'] += $unit->time;
                @$part[$partkey]['units'][$ident]['totalcount']++;
                @$part[$partkey]['units'][$ident]['units'][] = $unit;
            }
        }

        ob_start();
        print_r($this->log);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }
}