<?php

namespace Domain\Entity;

class UserFilter_Paid extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'multiselect';
        $this->multioptions = array('paid' => 'Платный', 'free' => 'Бесплатный');
    }
}