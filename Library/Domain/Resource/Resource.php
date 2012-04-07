<?php

namespace Domain\Resource;

/**
 * @property mixed $id
 * @property mixed $parent_id
 * @property mixed $type
 * @property mixed $creation_date
 * @property mixed $publish_date
 * @property mixed $status
 * @property mixed $title
 * @property mixed $description
 * @property mixed $copyright
 * @property mixed $file_path
 */

class Resource implements \ArrayAccess
{
    protected $__data__;

    public function __construct()
    {
        $this->file_path = "/indoc/{$this->file_path}";
    }

    public function __set($var, $value)
    {
        $this->__data__[$var] = $value;
    }

    public function __get($var)
    {
        return @$this->__data__[$var];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->__data__[] = $value;
        }
        else
        {
            $this->__data__[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->__data__[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->__data__[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->__data__[$offset]) ? $this->__data__[$offset] : null;
    }
}