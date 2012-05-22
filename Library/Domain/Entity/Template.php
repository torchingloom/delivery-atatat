<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $subject
 * @property mixed $from
 * @property mixed $body_plain
 * @property mixed $body_html
 */

class Template extends Entity
{
    public function toString()
    {
        return $this->name;
    }
}
