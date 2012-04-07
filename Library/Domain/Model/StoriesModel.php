<?php

namespace Domain\Model;

class StoriesModel extends SiteSectionModel
{
    protected function prepareCollectionsConfig()
    {
        parent::prepareCollectionsConfig();
        $this->collectionsConfig['Stories'] = array
        (
            'Collection' => '\Domain\Collection\Stories',
            'DataType' => 'Xml/Collection/ThreadList',
            'Params' => array_merge($this->INCOMING, array('limit' => 25, 'page' => $this->INCOMING['page']))
        );
    }
}
