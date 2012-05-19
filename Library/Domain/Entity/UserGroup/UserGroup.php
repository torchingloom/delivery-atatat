<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $when_create
 * @property mixed $algo
 * @property mixed $when_autofill
 */

class UserGroup extends Entity
{
    public function autofill()
    {
        if (!$this->algo)
        {
            throw new UserGroup_Exception("algo not defined for group {$this->id}");
        }
        return UserGroup_Autofill::factory($this->algo, $this)->fill();
    }

    public function appendUserPermanent(User $oUser)
    {

    }

    public function appendUserCollectionPermanent(\Domain\Collection\User $oCollection)
    {
        return \Service\Registry::get('db_default')->UserGroupAppendUser($this->id, $oCollection);
    }

    public function cleanAndFill(\Domain\Collection\User $oCollection)
    {
        return \Service\Registry::get('db_default')->UserGroupCleanAndFill($this->id, $oCollection);
    }

    public function appendUser(User $oUser)
    {
        parent::appendChild('users', $oUser);
    }

    public function appendUserTotalCount($iCount)
    {
        parent::setChildTotalCount('users', $iCount);
    }
}

class UserGroup_Exception extends EntityException
{

}