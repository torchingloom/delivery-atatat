<?php

namespace Domain\Model;

class User extends Model
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
            'Collection' => '\Domain\Collection\User',
            'DataType' => 'Xml/Collection/User',
            'Params' => $this->INCOMING
        );
    }
}