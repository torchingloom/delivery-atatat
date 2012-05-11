<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupClean extends DbCommand
{
    public function UserGroupClean($iGroupId)
    {
        return $this->_connection->delete("delivery_user_to_group", "group_id = {$iGroupId} AND status = 1");
    }
}
