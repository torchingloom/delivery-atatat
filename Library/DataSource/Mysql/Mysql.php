<?php

namespace DataSource\Mysql;

class Mysql
{
    /**
     * @var \DataSource\Mysql\Adapter
     */
    private $_connection;
    private $_functions = array ();
    private $_fetch_params = array();

    public function __construct($connection)
    {
        $this->_connection = $connection;
    }

    public function __call($functionName, $args)
    {
        if (empty($this->_functions[$functionName]))
        {
            $className = "\DataSource\Mysql\DbCommand\\{$functionName}";
            $this->_functions[$functionName] = new $className ($this->_connection);
        }
        
        if (\Service\Log\Log::isEnabled())
        {
            $LogUnit = \Service\Log\Log::instance('DB')->unit("{$functionName}", serialize(array_keys($args)));
        }

        $_result = call_user_func_array(array($this->_functions[$functionName], $functionName), $args);

        if (!empty($LogUnit))
        {
            $LogUnit->checkout();
        }
        
        return $_result;
    }
}

class MysqlException extends \DataSource\DbException
{


}
