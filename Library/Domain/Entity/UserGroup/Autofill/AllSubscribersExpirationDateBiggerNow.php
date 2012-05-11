<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow extends UserGroup_Autofill
{
    public function fill()
    {
        $result = array();

        $oModel = new \Domain\Model\SnobUser(array('expiration_date' => '> NOW()', 'person_type_id' => 4));
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $result['snobuser'] = $oCollection->store();

        $oModel = new \Domain\Model\User(array('subscribe_end_date' => '> NOW()', 'snob_person_type' => array('partner', 'premium', 'basic', 'starting')));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->appendUserCollectionPermanent($oCollection);

        return $result;
    }
}