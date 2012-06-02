<?php

namespace Domain\Model;

class UserGroupCategory extends Model
{
    protected function prepareCollectionsConfig()
    {
        $this->collectionsConfig['list'] = array
        (
            'Collection' => '\Domain\Collection\UserGroupCategory',
            'DataType' => 'Xml/Collection/UserGroupCategory',
            'Params' => $this->INCOMING
        );
    }
}

class UserGroupCategoryException extends \Exception
{
}