<?php

namespace DataSource\Mysql\DbCommand;

class TemplateSetter extends DbCommand
{
	public function TemplateSetter($params = array())
	{
        $result = array();
        foreach ($params AS $data)
        {
            if (!empty($data['id']))
            {
                $id = $data['id'];
                $result['update'][$id] = (bool) $this->_connection->update("delivery_template", $data, "id = {$id}");
            }
            else
            {
                if ($this->_connection->insert("delivery_template", $data))
                {
                    $result['create'][] = $this->_connection->lastInsertId("delivery_template");
                }
            }
        }
        return $result;
    }
}
