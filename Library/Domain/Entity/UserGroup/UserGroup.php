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

        return \Utils::arrayMergeRecursiveDistinct(array('group' => array('before' => 0, 'now' => 0), 'snobuser' => array('insert' => 0, 'update' => 0)), UserGroup_Autofill::factory($this->algo, $this)->fill());
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
        parent::appendChild('user', $oUser);
    }

    public function appendUserTotalCount($iCount)
    {
        parent::setChildTotalCount('user', $iCount);
    }

    public function toString()
    {
        return $this->name;
    }
}

class UserGroup_Exception extends EntityException
{

}