<?php

namespace DataSource\Mysql;

class Statement extends \Zend_Db_Statement_Pdo
{
    protected
        $mode = 8, //may be 'factory:class::method'
        $class = '\Domain\Entity\Entity',
        $factory = null,
        $key = array('id'),
        $keySeparator = '/'
    ;

    public function setFetchParams($mode = null, $class = null, $key = null, $keySeparator = null)
    {
        $this->setMode($mode);
        $this->setClass($class);
        $this->setKey($key, $keySeparator);
    }

    public function setMode($mode)
    {
        $this->mode = $mode ?: $this->mode;
    }

    public function setClass($class)
    {
        $this->class = $class ?: $this->class;
    }

    public function setFactory($factory)
    {
        $this->factory = explode('::', $factory);
    }

    public function setKey($key, $separator = null)
    {
        if ($key)
        {
            if (is_string($key))
            {
                return explode('/', $key);
            }
            $this->key = (array) $key;
        }
        $this->setKeySeparator($separator);
    }

    public function setKeySeparator($keySeparator)
    {
        $this->keySeparator = $keySeparator ?: $this->keySeparator;
    }

    public function setFetch($aFetchParams)
    {
        foreach ($aFetchParams AS $what => $val)
        {
            $this->{"set{$what}"}($val);
        }
    }

    public function fetchAll($mode = null, $class = null, $key = null)
    {
        $this->setFetchParams($mode, $class, $key);
        $result = array();
        $index = 0;
        foreach ($this->_stmt->fetchAll($this->mode, $this->class) AS $a)
        {
            $key = null;
            foreach ($this->key AS $fk)
            {
                if (isset($a->{$fk}) || isset($a[$fk]))
                {
                    $key .= ($key ? $this->keySeparator : '') . $a->{$fk};
                }
            }

            $result[$key ?: $index++] = $this->factory ? call_user_func($this->factory, $a) : $a;
//            $result[$key ?: $index++] = $a;
        }
        return $result;
    }

    public function fetchRow($mode = null, $class = null)
    {
        $result = null;
        $this->setFetchParams($mode, $class);
        if (is_array($tmp = $this->_stmt->fetchAll($this->mode, $this->class)))
        {
            $result = array_shift($tmp);
            $result = $this->factory ? call_user_func($this->factory, $result) : $result;
        }
        return $result;
    }

    public function fetchColumn($col = 0)
    {
        return $this->_stmt->fetchColumn($col);
    }

}