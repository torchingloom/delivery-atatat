<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSubscribersExpirationDateSmallerNow extends UserGroup_Autofill
{
    public function fill()
    {
        $result = array();

        $aModelParams = array('expiration_date' => '< NOW()', 'person_type_id' => 4);
        if ($this->oUserGroup->when_create)
        {
            $aModelParams['modification_date'] = "> '{$this->oUserGroup->when_autofill}'";
        }
        $oModel = new \Domain\Model\SnobUser($aModelParams);
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $result['snobuser'] = $oCollection->store();

        $oModel = new \Domain\Model\User(array('subscribe_end_date' => '< NOW()', 'snob_person_type' => array('partner', 'premium', 'basic', 'starting'), 'noorder' => 1));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->cleanAndFill($oCollection);

        return $result;
    }
}