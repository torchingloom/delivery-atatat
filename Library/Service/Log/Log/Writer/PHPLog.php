<?php

namespace Service\Log;

class Log_Writer_PHPLog extends Log_Writer
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
            if (!@$sectiondata['units'])
            {
                continue;
            }
            $sectiondata['totaltime'] = number_format($sectiondata['totaltime'], 6);
            $str .= "\n\n{$section}         {$sectiondata['totalcount']}            {$sectiondata['totaltime']}\n";
            foreach ($sectiondata['units'] AS $unitname => $unitdata)
            {
                $unitdata['totaltime'] = number_format($unitdata['totaltime'], 6);
                $str .= "       {$unitname}         {$unitdata['totalcount']}            {$unitdata['totaltime']}\n";
                foreach ($unitdata['units'] AS $unit)
                {
                    $unit->time = number_format($unit->time, 6);
                    $str .= "              [{$unit->time}] {$unit->msg}\n";
                }
            }
        }

        error_log("\n\n". date("H:i:s") ."{$_SERVER['HTTP_HOST']} {$_SERVER['REQUEST_URI']}\n\n{$str}");
    }
}