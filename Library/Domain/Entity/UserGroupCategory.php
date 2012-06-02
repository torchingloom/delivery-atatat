<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 */

class UserGroupCategory extends Entity
{
    public function appendGroup(UserGroup $oUser)
    {
        parent::appendChild('group', $oUser);
    }

    public function appendGroupTotalCount($iCount)
    {
        parent::setChildTotalCount('user', $iCount);
    }

    public function toString()
    {
        return $this->name;
    }
}

class UserGroupCategory_Exception extends EntityException
{

}