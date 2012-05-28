<?php

namespace Domain\Entity;

/**
 * @property mixed $groups
 * @property mixed $filters
 */

class Task_Test extends Task
{
    public function __construct($data)
    {
        $this->fill($data);
    }

    public function init()
    {
        foreach (\Service\Registry::get('db_default')->UserGetter(array('group_id' => $this->groups, 'filters' => array_merge($this->filters, array('now' => $this->when_start)), 'noorder' => 1)) AS $oUser)
        {
            $this->appendUser($oUser);
        }
    }
}
