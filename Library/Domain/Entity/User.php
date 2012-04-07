<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $login
 * @property mixed $email
 * @property mixed $password
 * @property mixed $access
 * @property mixed $role
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $display_name
 * @property mixed $position
 * @property mixed $bio
 * @property mixed $creation_date
 * @property mixed $last_logion_date
 * @property mixed $status
 * @property mixed $url
 */

class User extends Entity
{
    public static function factory(User $o)
    {
        switch ($o->role)
        {
            default:
                $class = '\Domain\Entity\User';
            break;
            case 'expert':
                $class = '\Domain\Entity\UserExpert';
            break;
        }

        $newo = new $class();
        $newo->fill($o->toArray());
        return $newo;
    }

    public function __construct()
    {
        if (!$this->first_name && !$this->last_name && !$this->display_name)
        {
            $this->display_name = $this->login;
        }
        else
        {
            $this->display_name = $this->display_name ?: "{$this->last_name} {$this->first_name}";
        }
        $this->url = "/expert/{$this->id}";
    }

    public function fill($array)
    {
        $this->__data__ = $array;
    }
    /**
     * @param \Domain\Entity\Entry $oEntry
     * @return void
     */
    public function appendEntry(Entry $oEntry)
    {
        parent::appendChild('Entry', $oEntry);
    }

    public function getEntryList()
    {
        return parent::getChilds('Entry');
    }

    public function setEntriesTotalCount($iCount)
    {
        parent::setChildTotalCount('Entry', $iCount);
    }

    public function getEntriesTotalCount()
    {
        return parent::getChildTotalCount('Entry');
    }
}
