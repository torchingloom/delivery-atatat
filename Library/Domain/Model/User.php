<?php

namespace Domain\Model;

class User extends Model
{
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