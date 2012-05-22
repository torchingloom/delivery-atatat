<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $email
 * @property mixed $login
 * @property mixed $sex
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $when_create
 * @property mixed $snob_user_id
 * @property mixed $snob_person_type
 * @property mixed $city
 * @property mixed $subscribe_start_date
 * @property mixed $subscribe_end_date
 * @property mixed $is_paid
 */


class User extends Entity
{
    public static function factory(User $o)
    {
        $class = '\Domain\Entity\User_NotRegister';
        if ($o->snob_user_id)
        {
            $class = '\Domain\Entity\User_Register';
        }
        $newo = new $class();
        $newo->fill($o->toArray());
        return $newo;
    }

    public function toString()
    {
        return "{$this->last_name} {$this->first_name} ({$this->email})";
    }
}
