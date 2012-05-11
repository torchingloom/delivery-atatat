<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSnobsExpirationDateBiggerNow extends UserGroup_Autofill
{
    public function fill()
    {
        $oModel = new \Domain\Model\SnobUser(array('expiration_date' => '> NOW()', 'person_type_id' => 1));
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $result['snobuser'] = $oCollection->store();

        $oModel = new \Domain\Model\User(array('subscribe_end_date' => '> NOW()', 'snob_person_type' => 'snob'));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->cleanAndFill($oCollection);

        return $result;
    }
}