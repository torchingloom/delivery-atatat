<?php

namespace Domain\Collection;

class UserGroup extends Collection
{
    public function autofill()
    {
        /* @var $oGroup \Domain\Entity\UserGroup */
        foreach ($this->content AS $oGroup)
        {
            $oGroup->autofill();
        }
    }
}