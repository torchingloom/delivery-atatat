<?php

namespace Domain\Entity;

/**
 * @property mixed $country
 */

class UserCountry extends Entity
{
    public function idGet()
    {
        return $this->toString();
    }

    public function toString()
    {
        return $this->country;
    }
}
