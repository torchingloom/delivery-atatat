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

    public function sendTest($data)
    {
        $users = array_keys(\Service\Registry::get('db_default')->UserGetter(array('group_id' => $data['groups'], 'filters' => array_merge($data['filters'], array('now' => $data['when_start'])), 'noorder' => 1)));
        \Utils::printr($data);
        \Utils::printr($users);
        exit();
    }

    public function toString()
    {
        return $this->name;
    }
}
