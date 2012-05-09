<?php

namespace DataSource\Mysql\DbCommand;

class SnobUserSetter extends DbCommand
{
	public function SnobUserSetter($params = array())
	{
        $result = array();
        foreach ($params AS $data)
        {
            $data = self::dataMap($data);
            if (!empty($data['id']))
            {
                $id = $data['id'];
                $result['update'][$id] = (bool) $this->_connection->update("delivery_user", $data, "id = {$id} ON DUPLICATE KEY UPDATE id = id");
            }
            else
            {
                if ($this->_connection->insert("delivery_user", $data))
                {
                    $result['create'][] = $this->_connection->lastInsertId("delivery_user");
                }
            }

        }

        return $result;
    }

    protected static function dataMap($data)
    {
        $fields = array
        (
            'id' => 'delivery_user_id',
            'email' => 'email',
            'login' => 'login',
            'sex' => 'sex',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'snob_user_id' => 'id',
            'snob_person_type' => ':type',
            'city' => 'city',
            'subscribe_start_date' => 'creation_date',
            'subscribe_end_date' => 'expiration_date',
            'invited_by_project' => 'invited_by_project',
        );

        foreach ($fields AS $name => &$valuev)
        {
            if (strpos($valuev, ':') === 0)
            {
                $m = "propertyCalc_". substr($valuev, 1);
                $valuev = self::$m($data);
                continue;
            }
            $valuev = $data[$valuev];
        }

        if (!$fields['id'])
        {
            unset($fields['id']);
        }

        return $fields;
    }
    
    protected static function propertyCalc_type($data)
    {
        switch ($data['person_type_id'])
        {
            case 4:
                return $data['subscribe_plan_name'];
                break;

            case 2:
                return 'editor';
                break;

            case 1:
                return 'snob';
                break;
        }
    }
}
