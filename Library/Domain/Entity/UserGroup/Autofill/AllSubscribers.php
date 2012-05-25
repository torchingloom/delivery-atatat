<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllSubscribers extends UserGroup_Autofill
{
    public function fill()
    {
        $result = array();

        if ($this->oUserGroup->when_create)
        {
            $aModelParams['modification_date'] = "> '{$this->oUserGroup->when_autofill}'";
        }
        $oModel = new \Domain\Model\SnobUser();
        /* @var $oCollection \Domain\Collection\SnobUser */
        $oCollection = $oModel->getCollection('list');
        $result['snobuser'] = $oCollection->store();

        $oModel = new \Domain\Model\User(array('noorder' => 1));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->cleanAndFill($oCollection);

        return $result;
    }
}