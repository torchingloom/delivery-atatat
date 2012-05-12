<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupSetter extends DbCommand
{
	public function UserGroupSetter($params = array())
	{
        $result = array();
        foreach ($params AS $data)
        {
            if (!empty($data['id']))
            {
                $id = $data['id'];
                $result['update'][$id] = (bool) $this->_connection->update("delivery_user_group", $data, "id = {$id}");
            }
            else
            {
                if ($this->_connection->insert("delivery_user_group", $data))
                {
                    $result['create'][] = $this->_connection->lastInsertId("delivery_user_group");
                }
            }
        }
        return $result;
    }
}
