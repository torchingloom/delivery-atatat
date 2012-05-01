<?php

namespace DataSource\Mysql\DbCommand;

class TemplateRemover extends DbCommand
{
	public function TemplateRemover($ids = array())
	{
        return $this->_connection->delete("delivery_template", "id IN (". join(',', (array) $ids) .")" );
    }
}
