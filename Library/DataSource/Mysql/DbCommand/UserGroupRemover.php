<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupRemover extends DbCommand
{
	public function UserGroupRemover($ids = array())
	{
        return $this->_connection->delete("delivery_user_group", "id IN (". join(',', (array) $ids) .")" );
    }
}
