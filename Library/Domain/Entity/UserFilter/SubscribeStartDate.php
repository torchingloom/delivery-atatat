<?php

namespace Domain\Entity;

class UserFilter_SubscribeStartDate extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'date';
    }
}