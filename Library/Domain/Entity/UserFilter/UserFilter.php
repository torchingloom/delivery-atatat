<?php

namespace Domain\Entity;

/**
 * @property mixed $name
 * @property mixed $title
 */


class UserFilter extends Entity
{
    public static function factory(UserFilter $o)
    {
        $class = __CLASS__ .'_'. preg_replace("/[-_]+(.)/e", 'strtoupper("\\1")', ucfirst($o->name));
        $newo = new $class ();
        $newo->fill($o->toArray());
        return $newo;
    }

    public function idGet()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->title;
    }
}