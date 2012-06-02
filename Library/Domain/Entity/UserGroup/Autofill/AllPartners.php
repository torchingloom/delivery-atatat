<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllPartners extends UserGroup_Autofill
{
    public function fill()
    {
        $result = array();

        $aModelParams = array('person_type_id' => 4, 'is_partner' => 1);
        if ($this->oUserGroup->when_create)
        {
            $aModelParams['modification_date'] = "> '{$this->oUserGroup->when_autofill}'";
        }
        $oModel = new \Domain\Model\SnobUser($aModelParams);
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $result['snobuser'] = $oCollection->store();

        $oModel = new \Domain\Model\User(array('snob_person_partner' => 1, 'noorder' => 1));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->cleanAndFill($oCollection);

        return $result;
    }
}