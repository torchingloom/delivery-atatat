<?php

namespace DataSource\Mysql\DbCommand;

class TaskSetter extends DbCommand
{
	public function TaskSetter($params = array())
	{
        $result = array();
        foreach ($params AS $data)
        {
            if (!empty($data['id']))
            {
                return;
            }
            else
            {
                $task = array_fill_keys(array('name', 'subject', 'from', 'body_html', 'body_plain', 'body_plain', 'when_start'), null);
                foreach ($task AS $key => & $valuev)
                {
                    $valuev = @$data[$key];
                }

                $users = array_keys($this->UserGetter(array('group_id' => $data['groups'], 'filters' => array_merge($data['filters'], array('now' => $data['when_start'])), 'noorder' => 1)));

                $this->beginTransaction();
                try
                {
                    $this->insert("delivery_task", $task);
                    if ($data['id'] = $this->lastInsertId("delivery_task"))
                    {
                        $this->query($sql = "INSERT INTO `delivery_user_to_task`\n(`task_id`, `user_id`)\nVALUES\n({$data['id']},". join("),\n({$data['id']},", $users) .")");
                        $this->commit();
                    }
                    $result[] = $data['id'];
                }
                catch (\Exception $e)
                {
                    $this->rollback();
                }
            }
        }
        return $result;
    }
}
