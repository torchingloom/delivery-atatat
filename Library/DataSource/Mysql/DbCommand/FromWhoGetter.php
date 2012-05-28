<?php

namespace DataSource\Mysql\DbCommand;

class FromWhoGetter extends DbCommand
{
	public function FromWhoGetter($params = array())
	{
        $result = array();
        foreach (\Service\Config::get('mail.from') AS $from)
        {
            $o = new \Domain\Entity\FromWho();
            $o->from = $from;
            $result[$from] = $o;
        }
        return $result;
    }
}
