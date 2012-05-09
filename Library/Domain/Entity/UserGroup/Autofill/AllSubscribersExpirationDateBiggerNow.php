<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow extends UserGroup_Autofill
{
    public function fill()
    {
        $oModel = new \Domain\Model\SnobUser(array('expiration_date' => '> NOW()', 'person_type_id' => 4));
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $oCollection->store();


//        \Service\Registry::get('db_default')->UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow(array('group_id' => $this->oUserGroup->id));
    }
}