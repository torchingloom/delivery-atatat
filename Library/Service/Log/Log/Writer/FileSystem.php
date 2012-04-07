<?php

namespace Service\Log;

class Log_Writer_FileSystem extends Log_Writer
{
    protected function put()
    {
        $str = '';
        if (!$this->log)
        {
            return;
        }
        foreach ($this->log AS $section => $sectiondata)
        {
            if (!$sectiondata['units'])
            {
                continue;
            }
            $str .= "\n\n{$section}         {$sectiondata['totalcount']}            {$sectiondata['totaltime']}\n";
            foreach ($sectiondata['units'] AS $unitname => $unitdata)
            {
                $str .= "       {$unitname}         {$unitdata['totalcount']}            {$unitdata['totaltime']}\n";
                foreach ($unitdata['units'] AS $unit)
                {
                    $str .= "              [{$unit->time}] {$unit->msg}\n";
                }
            }
        }
//        error_log("\n\n". date("H:i:s") ."{$_SERVER['HTTP_HOST']}  {$_SERVER['REQUEST_URI']}\n\n{$str}");
    }
}