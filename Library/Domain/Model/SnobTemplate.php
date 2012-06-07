<?php

namespace Domain\Model;

class SnobTemplate extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\SnobTemplate',
            'DataType' => 'Xml/Collection/SnobTemplate',
            'Params' => $this->INCOMING
        );
    }
}