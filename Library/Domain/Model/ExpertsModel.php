<?php

namespace Domain\Model;

class ExpertsModel extends SiteSectionModel
{
    protected function prepareCollectionsConfig()
    {
        parent::prepareCollectionsConfig();
        $this->collectionsConfig['Experts'] = array
        (
            'Collection' => '\Domain\Collection\Experts',
            'DataType' => 'Xml/Collection/ExpertList',
            'Params' => array_merge($this->INCOMING, array('limit' => 50, 'page' => $this->INCOMING['page']))
        );
        $this->collectionsConfig['ExpertsLetters'] = array
        (
            'Collection' => '\Domain\Collection\ExpertsLetters',
            'DataType' => 'Xml/Collection/ExpertLettersList',
            'Params' => $this->INCOMING
        );
    }
}
