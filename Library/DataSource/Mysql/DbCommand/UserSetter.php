<?php

namespace DataSource\Mysql\DbCommand;

class UserSetter extends DbCommand
{
	public function UserSetter($params = array())
    {
        $result = array();
        foreach ($params AS $data)
        {
            if (!empty($data['id']))
            {
                $id = $data['id'];
                $result['update'][$id] = (bool) $this->_connection->update("delivery_user", $data, "id = {$id}");
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
}
