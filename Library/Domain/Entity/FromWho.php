<?php

namespace Domain\Entity;

/**
 * @property string $from
 */

class FromWho extends Entity
{
    public function idGet()
    {
        return $this->from;
    }

    public function toString()
    {
        return $this->idGet();
    }
}