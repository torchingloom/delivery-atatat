<?php

namespace DataSource\Mysql\DbCommand;

class TaskTryMarkAsComplete extends DbCommand
{
	public function TaskTryMarkAsComplete($taskid)
	{
        if ($this->query($sql = "SELECT COUNT(*) FROM `delivery_user_to_task` WHERE `task_id` = {$taskid} AND `when_send` IS NULL")->fetchColumn())
        {
            return false;
        }
        $this->query($sql = "UPDATE `delivery_task` SET `status` = 'completed' WHERE `id` = {$taskid}");
        return $this->query($sql = "SELECT `status` FROM `delivery_task` WHERE `id` = {$taskid}")->fetchColumn();
    }
}
