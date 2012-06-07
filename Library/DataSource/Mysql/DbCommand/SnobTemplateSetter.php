<?php

namespace DataSource\Mysql\DbCommand;

class SnobTemplateSetter extends DbCommand
{
	public function SnobTemplateSetter($params = array())
	{
        $from = current(\Service\Registry::get('db_default')->FromWhoGetter())->from;

        foreach ($params AS &$param)
        {
            $tmp = $param;
            $param = array('name' => $tmp->name, 'subject' => $tmp->name, 'from' => $from, 'body_html' => $tmp->body);
        }
        return \Service\Registry::get('db_default')->TemplateSetter($params);
    }
}
