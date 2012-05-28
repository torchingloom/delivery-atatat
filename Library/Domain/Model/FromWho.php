<?php

namespace Domain\Model;

class FromWho extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\FromWho',
            'DataType' => 'Xml/Collection/FromWho',
            'Params' => $this->INCOMING
        );
    }
}
