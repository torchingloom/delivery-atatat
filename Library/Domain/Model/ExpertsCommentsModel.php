<?php

namespace Domain\Model;

class ExpertsCommentsModel extends SiteSectionModel
{
    protected function prepareCollectionsConfig()
    {
        parent::prepareCollectionsConfig();
        $this->collectionsConfig['ExpertsComments'] = array
        (
            'Collection' => '\Domain\Collection\ExpertsComments',
            'DataType' => 'Xml/Collection/ExpertsCommentsListWithEntry',
            'Params' => array('page' => $this->INCOMING['comments-page'], 'limit' => 25)
        );
    }

}