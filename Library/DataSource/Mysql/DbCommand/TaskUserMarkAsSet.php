<?php

namespace DataSource\Mysql\DbCommand;

class TaskUserMarkAsSet extends DbCommand
{
	public function TaskUserMarkAsSet($taskid, $who)
	{
        return $this->query($sql = "UPDATE `delivery_user_to_task` SET `when_send` = NOW() WHERE `task_id` = {$taskid} AND `user_id` IN (". join(',', (array) $who) .")")->rowCount();
    }
}
