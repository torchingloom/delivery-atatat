<?php

namespace Domain\Entity;

class UserGroup_Autofill_AllGuests extends UserGroup_Autofill
{
    public function fill()
    {
        $result = array();

        $oModel = new \Domain\Model\User(array('snob_user_id' => 'IS NULL', 'noorder' => 1));
        $oCollection = $oModel->getCollection('list');
        $result['group'] = $this->oUserGroup->cleanAndFill($oCollection);

        return $result;
    }
}