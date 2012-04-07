<?php

namespace Domain\Entity;

class EntryGroupSectionStatic extends EntryGroup
{
    public function __get($var)
    {
        if ($var == 'body' && empty($this->__data__['body']))
        {
            if ($oExtra = \Service\Registry::get('db')->EntryGroupExtraById($this->id))
            {
                $this->__data__ = array_merge($oExtra->toArray(), $this->__data__);
            }
        }
        return parent::__get($var);
    }
}
