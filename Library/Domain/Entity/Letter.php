<?php

namespace Domain\Entity;

class Letter extends Entity
{
    public function __set($var, $value)
    {
        $this->__data__['letter'] = $value;
    }

    public function __toString()
    {
        return (string) $this->__data__['letter'];
    }
}
