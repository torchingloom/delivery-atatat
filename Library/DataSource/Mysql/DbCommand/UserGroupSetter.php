<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupSetter extends DbCommand
{
	public function UserGroupSetter($params = array())
	{
        $users = $result = array();
        foreach ($params AS $data)
        {
            $id = null;
            $usersTMP = array();
            if (array_key_exists('users', $data))
            {
                $usersTMP = $data['users'];
                unset($data['users']);
            }

            if (!empty($data['id']))
            {
                $id = $data['id'];
                $result['update'][$id] = (bool) $this->_connection->update("delivery_user_group", $data, "id = {$id}");
            }
            else
            {
                try
                {
                    if ($this->_connection->insert("delivery_user_group", $data))
                    {
                        $result['create'][] = $id = $this->_connection->lastInsertId("delivery_user_group");
                    }
                }
                catch (\Exception $e)
                {
                }
            }

            if ($id && $usersTMP)
            {
                $users[$id] = $usersTMP;
            }
        }

        if ($users)
        {
            $this->_connection->delete("delivery_user_to_group", "group_id IN (". join(',', array_keys($users)) .") AND status > 0"); //не удалять скрытых
            foreach ($users AS $griupid => $userids)
            {
                $sql = "INSERT INTO `delivery_user_to_group` (`group_id`, `user_id`) VALUES ({$griupid}, ". join("),({$griupid},", $userids) .") ON DUPLICATE KEY UPDATE `user_id` = `user_id`;";
                $this->_connection->query($sql);
            }

        }

        return $result;
    }
}
