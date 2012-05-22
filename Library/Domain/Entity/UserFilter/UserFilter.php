<?php

namespace Domain\Entity;

/**
 * @property string $name
 * @property string $title
 * @property string $kind
 * @property string $options
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

    protected function init()
    {
        $this->kind = 'default';
    }

    public function idGet()
    {
        return $this->name;
    }

    public function toString()
    {
        return $this->title;
    }
}