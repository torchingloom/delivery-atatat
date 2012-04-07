<?php

namespace DataSource\Mysql\DbCommand;

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