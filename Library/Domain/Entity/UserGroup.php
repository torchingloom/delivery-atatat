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
        UserGroup_Autofill::factory($this->algo, $this)->fill();
    }
}

class UserGroup_Exception extends EntityException
{

}