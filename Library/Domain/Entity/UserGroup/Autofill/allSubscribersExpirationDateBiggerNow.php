<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow extends UserGroup_Autofill
{
    public function fill()
    {
        $oModel = new \Domain\Model\SnobUser(array('expiration_date' => '> NOW()'));
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        \Utils::printr($oCollection); exit();
    }
}