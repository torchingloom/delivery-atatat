<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupTotalCount extends DbCommand
{
    public function UserGroupTotalCount($iGroupId)
    {
        $sql_COUNT = "SELECT COUNT(*) FROM `delivery_user_to_group` WHERE `group_id` = {$iGroupId}";
        return $this->_connection->query($sql_COUNT)->fetchColumn();
    }
}
