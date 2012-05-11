<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupAppendUser extends DbCommand
{
    public function UserGroupAppendUser($iGroupId, $params)
    {
        if ($params instanceof \Domain\Collection\User)
        {
            return $this->appendMany($iGroupId, $params);
        }
        else
        {
            return $this->appendOne($iGroupId, $params);
        }
    }

    protected function appendOne($iGroupId, $params)
    {

    }

    /**
     * @param $iGroupId
     * @param $params \Domain\Collection\User
     */
    protected function appendMany($iGroupId, $params)
    {
        $result = array('before' => $this->howManyUsers($iGroupId), 'now' => 0);
        if ($params = $params->toArray())
        {
            $sql = "INSERT INTO `delivery_user_to_group`\n(`group_id`, `user_id`)\nVALUES\n({$iGroupId}, ". join("),\n({$iGroupId}, ", array_keys($params)) .")\nON DUPLICATE KEY UPDATE `user_id` = `user_id`";
            $this->_connection->query($sql);
            $result['now'] = $this->howManyUsers($iGroupId);
        }
        else
        {
            $result['now'] = $result['before'];
        }
        return $result;
    }

    protected function howManyUsers($iGroupId)
    {
        $sql_COUNT = "SELECT COUNT(*) FROM `delivery_user_to_group` WHERE `group_id` = {$iGroupId}";
        return $this->_connection->query($sql_COUNT)->fetchColumn();
    }
}
