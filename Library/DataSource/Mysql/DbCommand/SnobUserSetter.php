<?php

namespace DataSource\Mysql\DbCommand;

class SnobUserSetter extends DbCommand
{
	public function SnobUserSetter($params = array())
	{
        $arr = array('insert' => array(), 'update' => array());
        foreach ($params AS $data)
        {
            if (!($data = self::dataMap($data)))
            {
                continue;
            }
            if (!empty($data['id']))
            {
                $arr['update'][$data['id']] = $data;
            }
            else
            {
                $arr['insert'][] = $data;
            }

        }

        $sql = '';
        foreach ($arr['insert'] AS $item)
        {
            $sql .= ($sql ? ",\n" : '') . str_replace("''", "NULL", "('". join("', '", $item) ."')");
        }

        if ($sql)
        {
            $sql = "INSERT INTO `delivery_user`\n(`". join('`, `', array_slice(array_keys(self::fields()), 2)) ."`)\nVALUES\n" . $sql;
            $this->_connection->query($sql);
        }

        foreach ($arr['update'] AS $item)
        {
            $sql = '';
            foreach ($item AS $name => $valuev)
            {
                if ($name == 'id')
                {
                    continue;
                }
                $sql .= ($sql ? ',' : '') ."\n`{$name}` = '{$valuev}'";
            }
            $sql = str_replace("''", "NULL", "UPDATE\n`delivery_user`\nSET{$sql}\nWHERE `id` = {$item['id']}");
            $this->_connection->query($sql);
        }

        return array('insert' => count($arr['insert']), 'update' => count($arr['update']));
    }

    protected static function dataMap($data)
    {
        $fields = self::fields();

        foreach ($fields AS $name => &$valuev)
        {
            if (method_exists(__CLASS__, $m = "propertyCalc_". $name))
            {
                $valuev = addslashes(self::$m($data));
                continue;
            }
            $valuev = addslashes($data[$valuev]);
        }

        if ($fields['email_already_exists'] && !$fields['id'])
        {
            return null;
        }

        if (!$fields['id'])
        {
            unset($fields['id']);
        }

        unset($fields['email_already_exists']);

        return $fields;
    }
    
    protected static function propertyCalc_snob_person_type($data)
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

    protected static function propertyCalc_sex($data)
    {
        return (int) $data['sex'];
    }

    protected static function propertyCalc_city($data)
    {
        $alias = array
        (
            'moscow' => '/(.*москва.*|.*москов.*|мск|msk|moskva|.*moscow.*|.*мо .*)/imsu',
            'london' => '/.*london.*/imsu',
            'ny' => '/(ny|.*new.*?york.*)/imsu',
        );
        if ($data['city'])
        {
            foreach ($alias AS $city => $reg)
            {
                if (preg_match($reg, $data['city']))
                {
                    $data['city'] = $city;
                    break;
                }
            }
        }
        return $data['city'];
    }

    protected static function propertyCalc_subscribe_start_date($data)
    {
        return self::propertyCalc_anydate($data['creation_date']);
    }

    protected static function propertyCalc_subscribe_end_date($data)
    {
        return self::propertyCalc_anydate($data['expiration_date']);
    }

    protected static function propertyCalc_anydate($date)
    {
        if (!($date = @strtotime($date)))
        {
            return null;
        }
        return date('Y-m-d H:i:s', $date);
    }

    protected static function fields()
    {
        return array
        (
            'email_already_exists' => 'delivery_user_email_exists',
            'id' => 'delivery_user_id',
            'email' => 'email',
            'login' => 'login',
            'sex' => 'sex',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'snob_user_id' => 'id',
            'snob_person_type' => 'snob_person_type',
            'snob_person_partner' => 'partner',
            'city' => 'city',
            'country' => 'country',
            'subscribe_start_date' => 'creation_date',
            'subscribe_end_date' => 'expiration_date',
            'is_paid' => 'is_paid',
        );
    }
}
