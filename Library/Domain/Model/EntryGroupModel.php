<?php

namespace Domain\Model;

class EntryGroupModel extends SiteSectionModel
{
    protected function prepareNewslist()
    {
        $this->collectionsConfig['Entries'] = array
        (
            'Collection' => '\Domain\Collection\Entry',
            'DataType' => 'Xml/Collection/EntryListWithAuthorByGroupAndRegionCheck',
            'Params' => array_merge($this->getEntryGroup()->toArray(), array('limit' => 15, 'region' => $this->INCOMING['region'], 'page' => $this->INCOMING['page'], 'id' => $this->getEntryGroup()->id))
        );
    }
}