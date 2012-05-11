<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $when_create
 * @property mixed $algo
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
}

class UserGroup_Exception extends EntityException
{

}