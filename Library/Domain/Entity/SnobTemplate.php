<?php

namespace Domain\Entity;

/**
 * @property string id
 * @property string body
 * @property string name
 * @property string filename
 * @property string description
 * @property string creation_date
 */

class SnobTemplate extends Entity
{
    public function __get($var)
    {
        if ($var == 'body' && !@$this->__data__['body'])
        {
            $this->__data__['body'] = @file_get_contents(\Service\Config::get('templates.old_path') . $this->filename .'.html');
        }
        return parent::__get($var);
    }
}
