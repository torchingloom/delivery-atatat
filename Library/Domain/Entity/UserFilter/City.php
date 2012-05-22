<?php

namespace Domain\Entity;

class UserFilter_City extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'multiselect';
        $this->options = array('options' => array('moscow' => 'Москва', 'london' => 'Лондон', 'ny' => 'Нью Йорк'));
    }
}