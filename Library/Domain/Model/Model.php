<?php

namespace Domain\Model;

class Model implements \ArrayAccess, \Iterator, \Countable
{
    protected
        $collectionsConfig = array(),
        $collections = array()
    ;
    
    public function __construct()
    {
        $this->initCollections();
    }

    public function initCollections()
    {
        foreach($this->collectionsConfig AS $index => $_classParams)
        {
            $_className = @$_classParams['Collection'] ?: $index;
            if (class_exists($_className))
            {
                $this->collections[$index] = new $_className($_classParams);
            }
            else
            {
                throw new ModelException("\n\nКласс {$_className} просто не существует! \n\nА он указан в списке необходимых коллекций для модели : ".__NAMESPACE__.'\\'.__CLASS__." \n\nТакие дела");
            }
        }
    }

    public function getCollections()
    {
        return $this->collections;
    }

    public function getCollectionsConfig()
    {
        return $this->collectionsConfig;
    }

    public function getCollectionConfig($offset)
    {
        return $this->collectionsConfig[$offset];
    }

    /**
     * @param $offset
     * @return \Domain\Collection\Collection
     */
    public function getCollection($offset)
    {
        return $this->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->collections[] = $value;
        }
        else
        {
            $this->collections[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->collections[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->collections[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->collections[$offset]) ? $this->collections[$offset] : null;
    }

    public function rewind()
    {
        reset($this->collections);
    }

    public function current()
    {
        return current($this->collections);
    }

    public function key()
    {
        return key($this->collections);
    }

    public function next()
    {
        return next($this->collections);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function count()
    {
        return count($this->collections);
    }

    public function toArray()
    {
        return $this->collections;
    }
}

class ModelException extends \Exception
{

}