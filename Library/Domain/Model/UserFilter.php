<?php

namespace Domain\Model;

class UserFilter extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\UserFilter',
            'DataType' => 'Xml/Collection/UserFilter',
            'Params' => $this->INCOMING
        );
    }
}
