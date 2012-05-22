<?php

namespace Domain\Model;

class SnobUser extends Model
{
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