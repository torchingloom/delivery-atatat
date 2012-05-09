<?php

namespace Domain;

class Collection implements \ArrayAccess, \Iterator, \Countable
{
    protected $content = array();

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->content[] = $value;
        }
        else
        {
            $this->content[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->content[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->content[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->content[$offset]) ? $this->content[$offset] : null;
    }

    public function rewind()
    {
        reset($this->content);
    }

    public function current()
    {
        return current($this->content);
    }

    public function key()
    {
        return key($this->content);
    }

    public function next()
    {
        return next($this->content);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function count()
    {
        return count($this->content);
    }

    public function toArray()
    {
        $result = array();
        foreach ($this->content AS $key => $item)
        {
            $result[$key] = $item->toArray();
        }
        return $result;
    }
}
