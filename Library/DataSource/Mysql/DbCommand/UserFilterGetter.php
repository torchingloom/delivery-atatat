<?php

namespace DataSource\Mysql\DbCommand;

class UserFilterGetter extends DbCommand
{
    protected $filters = array
    (
        'paid' => 'Платный или бесплатный аккаут',
        'city' => 'Город',
        'country' => 'Страна',
        'subscribe_end_date' => 'Дата окончания подписки не позже',
        'subscribe_start_date' => 'Дата начала подписки позже',
    );

	public function UserFilterGetter($params = array())
	{
        $result = array();
        foreach ($this->filters AS $name => $title)
        {
            $o = new \Domain\Entity\UserFilter;
            $o->name = $name;
            $o->title = $title;
            $result[$name] = \Domain\Entity\UserFilter::factory($o);
        }
        return $result;
    }
}
