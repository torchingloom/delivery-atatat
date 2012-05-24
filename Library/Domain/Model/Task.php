<?php

namespace Domain\Model;

class Task extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\Task',
            'DataType' => 'Xml/Collection/Task',
            'Params' => $this->INCOMING
        );
    }
}

class TemplateException extends \Exception
{
}