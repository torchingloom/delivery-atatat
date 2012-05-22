<?php

namespace DataSource\Mysql\DbCommand;

class UserFilterGetter extends DbCommand
{
    protected $filters = array
    (
        'paid' => 'Платный аккаут',
        'free' => 'Беcплатный аккаут',
        'city-london' => 'Город - Лондон',
        'city-ny' => 'Город - NY',
        'city-moscow' => 'Город - Москва',
        'subscribe_end_date' => 'Дата окончания подписки',
        'subscribe_start_date' => 'Дата начала подписки',
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
