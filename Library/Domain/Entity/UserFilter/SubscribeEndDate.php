<?php

namespace Domain\Entity;

class UserFilter_SubscribeEndDate extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'date';
    }
}