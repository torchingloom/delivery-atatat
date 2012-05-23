<?php

namespace Domain\Entity;

class UserFilter_Country extends UserFilter
{
    protected function init()
    {
        parent::init();
        $this->kind = 'multiselect';
        foreach (\Service\Registry::get('db_default')->UserCountryGetter() AS $oCountry)
        {
            $multioptions[$oCountry->idGet()] = $oCountry->toString();
        }
        $this->multioptions = $multioptions;
    }
}