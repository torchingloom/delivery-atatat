<?php

namespace Domain\Model;

class SnobUser extends Model
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
            'Collection' => '\Domain\Collection\SnobUser',
            'DataType' => 'Xml/Collection/SnobUser',
            'Params' => $this->INCOMING
        );
    }
}