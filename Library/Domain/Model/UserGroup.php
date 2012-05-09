<?php

namespace Domain\Model;

class UserGroup extends Model
{
    protected $INCOMING;

    public function __construct($params = array())
    {
        $this->INCOMING = $params;
        $this->prepareCollectionsConfig();
        parent::__construct();
    }

    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\UserGroup',
            'DataType' => 'Xml/Collection/UserGroup',
            'Params' => $this->INCOMING
        );
    }
}

class TemplateException extends \Exception
{
}