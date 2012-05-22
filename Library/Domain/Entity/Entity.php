<?php

namespace Domain\Entity;

class Entity implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array obj flds
     */
    protected $__data__ = array();
    /**
     * @var array of nested Entity
     */
    protected $content = array();
    /**
     * @var array of nested Resource
     */
    protected $resources = array();

    public function __construct($args = array())
    {
        $this->init();
    }

    protected function init()
    {
        // virtual
    }

    public function appendChild($kind, Entity $object, $key = 'id')
    {
        if ($key === null)
        {
            $this->content[$kind] = $object;
        }
        else
        {
            $this->content[$kind][$object->{$key}] = $object;
        }
    }

    public function setChildTotalCount($kind, $count)
    {
        $this->content['totalCount'][$kind] = $count;
    }

    public function getChildTotalCount($kind)
    {
        return @$this->content['totalCount'][$kind];
    }

    public function getChild($kind, $key)
    {
        if (empty($this->content[$kind][$key]))
        {
            return null;
        }
        return $this->content[$kind][$key];
    }

    public function getChilds($kind)
    {
        if (empty($this->content[$kind]))
        {
            return null;
        }
        return $this->content[$kind];
    }

    public function appendResources($aResources)
    {
        foreach ($aResources AS $resource)
        {
            $this->appendResource($resource);
        }
    }

    public function appendResource(\Domain\Resource\Resource $resource)
    {
        $this->resources[$resource->type][$resource->id] = $resource;
    }

    public function getResourcesByType($type)
    {
        if (empty($this->resources[$type]))
        {
            return null;
        }
        return $this->resources[$type];
    }

    public function idGet()
    {
        return $this->id;
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

    public function rewind()
    {
        reset($this->__data__);
    }

    public function current()
    {
        return current($this->__data__);
    }

    public function key()
    {
        return key($this->__data__);
    }

    public function next()
    {
        return next($this->__data__);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function count()
    {
        return count($this->__data__);
    }

    public function fill($array)
    {
        $this->__data__ = $array;
        $this->init();
    }

    public function toArray()
    {
        return $this->__data__;
    }

    public function toString()
    {
        ob_start();
        print_r($this->__data__);
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function __toString()
    {
        return $this->toString();
    }
}

class EntityException extends \Exception
{
}