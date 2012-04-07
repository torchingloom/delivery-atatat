<?php

namespace Service\Cache;

class Server_LogWriter
{
    protected $log;
    protected static $cfg;

    public static function &instance()
    {
        static $o;
        if (!$o)
        {
            $o = new self;
        }
        return $o;
    }


    public function put($what, $msg)
    {
        $what = explode('::', str_replace('Server_', '', $what));
        $this->log[$what[0]][$what[1]][] = $msg;
    }

    protected function __construct()
    {
        
    }

    public function __destruct()
    {
        self::$cfg = Zend_Registry::get('config')->log;
        $this->write();
    }

    protected function write()
    {
        if (!self::$cfg->write)
        {
            return;
        }

        switch (self::$cfg->place)
        {
            default:
                $this->write2FileSystem();
            break;

        	case 'php_log':
                $this->write2PhpLog();
        	break;
        }
    }

    protected function write2FileSystem()
    {
        $logfolder = self::$cfg->folder;
        foreach ($this->log AS $server => $ops)
        {
            foreach ($ops AS $op => $msgs)
            {
                $handle = fopen("{$logfolder}/". join('-', array(date('Ymd--H-'), $server, $op)) .".txt", "a");
                fwrite($handle, "\n\n". date("H:i:s") ." {$_SERVER['REQUEST_URI']}\n");
                foreach ($msgs AS $msg)
                {
                    fwrite($handle, "    {$msg}\n");
                }
                fclose($handle);
            }
        }
    }

    protected function write2PhpLog()
    {
        foreach ($this->log AS $server => $ops)
        {
            foreach ($ops AS $op => $msgs)
            {
                error_log("\n\n". date("H:i:s") ." {$_SERVER['REQUEST_URI']} {$server} [{$op}]");
                foreach ($msgs AS $msg)
                {
                    error_log("    {$msg}");
                }
            }
        }
    }
}
