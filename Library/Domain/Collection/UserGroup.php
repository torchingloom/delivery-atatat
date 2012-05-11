<?php

namespace Domain\Collection;

class UserGroup extends Collection
{
    public function autofill()
    {
        $result = array();
        /* @var $oGroup \Domain\Entity\UserGroup */
        foreach ($this->content AS $oGroup)
        {
            $result[$oGroup->id] = array('group' => $oGroup, 'result' => $oGroup->autofill());
        }
        return $result;
    }
}