<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupFieldSetter extends DbCommand
{
	public function UserGroupFieldSetter(array $data)
	{
        $result = array();
        foreach ($data AS $iGroupId => $valuev)
        {
            $result[$iGroupId] = (bool) $this->_connection->update("delivery_user_group", $valuev, "id = {$iGroupId}");
        }
        return $result;
    }
}
