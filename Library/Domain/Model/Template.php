<?php

namespace Domain\Model;

class Template extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\Template',
            'DataType' => 'Xml/Collection/Template',
            'Params' => $this->INCOMING
        );
    }
}

class TemplateException extends \Exception
{
}