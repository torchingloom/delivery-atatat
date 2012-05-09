<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $email
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $when_create
 */


class User extends Entity
{
    public function store()
    {
        \Service\Registry::get('db_default')->UserSetter($this->toArray());
    }
}
