<?php

namespace DataSource\Mysql\DbCommand;

/**
 * @method \DataSource\Mysql\Adapter beginTransaction()
 * @method \DataSource\Mysql\Adapter rollback()
 * @method \DataSource\Mysql\Adapter commit()
 * @method \int insert($table, array $bind)
 * @method \int update($table, array $bind, $where)
 * @method \int lastInsertId($tableName = null, $primaryKey = null)
 * @method \Zend_Db_Statement_Pdo query($sql, $bind = array())
 */

class DbCommand
{
    /**
     * @var \DataSource\Mysql\Adapter
     */
    protected $_connection;

    protected static $results = array();

	public function __construct($connection)
	{
		$this->_connection = $connection;
	}

    public function __call($name, $arguments)
    {
        if (method_exists($this->_connection, $name))
        {
            return call_user_func_array(array($this->_connection, $name), $arguments);
        }
        return call_user_func_array(array(\Service\Registry::get('db_default'), $name), $arguments);
    }

	protected function ParamsAndFieldsPrepareByMethod(array $params = array(), array $defaults = array(), array $force = array())
	{
        $params = array_merge($defaults, $params, $force);

        if (empty($params['__FIELDS__']))
        {
            $params['__FIELDS__'] = array();
        }
        if (empty($defaults['__FIELDS__']))
        {
            $defaults['__FIELDS__'] = array();
        }
        $params['__FIELDS__'] = array_merge($defaults['__FIELDS__'], $params['__FIELDS__']);
        $fields = array();
        foreach($params['__FIELDS__'] AS $name => $data)
        {
            $fields[] = "{$name} AS ". (@$data['alias'] ?: preg_replace('/.*\./', '', $name));
        }
        $params['__FIELDS__'] = $fields;

        if (empty($params['__FETCH__']))
        {
            $params['__FETCH__'] = array();
        }
        if (empty($defaults['__FETCH__']))
        {
            $defaults['__FETCH__'] = array();
        }
        $params['__FETCH__'] = array_merge($defaults['__FETCH__'], $params['__FETCH__']);

        if (!empty($params['FETCH_KEY']))
        {
            $params['__FETCH__']['key'] = $params['FETCH_KEY'];
            unset($params['FETCH_KEY']);
        }

        return $params;
	}

	protected static function cacheSidByMethod($method, $params = array())
	{
        $method = substr($method, strrpos($method, '\\') + 1);
	    return \Service\Cache\Implementation_DB::cacheSidByMethod($method, $params);
	}

	protected static function cacheExists($sid)
	{
	    return \Service\Cache\Implementation_DB::exists($sid);
	}

	protected static function cacheGet($sid)
	{
	    return \Service\Cache\Implementation_DB::get($sid);
	}

	protected static function cacheSet($sid, $value, $lifetime = null, $tags = array())
	{
	    return \Service\Cache\Implementation_DB::set($sid, $value, $lifetime, $tags);
	}

	protected static function cacheTagsByMap($value, $map, $tags = array())
	{
        if ($value && $map)
        {
            foreach ($value AS $val)
            {
                foreach ($map AS $field => $tagprefix)
                {
                    $tags[] = "{$tagprefix}{$val->{$field}}";
                }
            }
        }
        return $tags;
	}
}