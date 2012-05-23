<?php

namespace Domain\Entity;

class UserFilter_City extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'Multiselect';
        $this->multioptions = array('moscow' => 'Москва', 'london' => 'Лондон', 'ny' => 'Нью Йорк');
    }
}