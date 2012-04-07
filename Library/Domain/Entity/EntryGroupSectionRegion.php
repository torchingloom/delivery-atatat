<?php

namespace Domain\Entity;

/**
 * @property string $region
 */

class EntryGroupSectionRegion extends EntryGroup
{
    public function __set($var, $value)
    {
        parent::__set($var, $value);
        if ($var == 'name')
        {
            $this->region = str_replace('main_', '', $value);
        }
    }
}
