<?php

namespace DataSource\Mysql\DbCommand;

class UserFilterGetter extends DbCommand
{
	public function UserFilterGetter($params = array())
	{
        $result = array();
        foreach (\Service\Config::get('userfilter') AS $name => $title)
        {
            $o = new \Domain\Entity\UserFilter;
            $o->name = $name;
            $o->title = $title;
            $result[$name] = \Domain\Entity\UserFilter::factory($o);
        }
        return $result;
    }
}
