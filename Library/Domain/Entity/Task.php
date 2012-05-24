<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $subject
 * @property mixed $from
 * @property mixed $body_plain
 * @property mixed $body_html
 * @property mixed $when_start
 * @property mixed $type
 * @property mixed $status
 */

class Task extends Entity
{
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
