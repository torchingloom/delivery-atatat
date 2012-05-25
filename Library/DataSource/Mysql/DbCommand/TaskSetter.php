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
                $task = array_fill_keys(array('subject', 'from', 'body_html', 'body_plain', 'body_plain', 'when_start'), null);
                foreach ($task AS $key => & $valuev)
                {
                    $valuev = $data[$key];
                }

                $users = $this->UserGetter(array('group_id' => $data['groups'], 'filters' => $data['filters'], 'noorder' => 1));

                $this->beginTransaction();
                try
                {
                    $this->insert("delivery_task", $task);
                    $data['id'] = $this->lastInsertId("delivery_task");
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
