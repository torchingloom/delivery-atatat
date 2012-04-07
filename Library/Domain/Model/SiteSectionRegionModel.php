<?php

namespace Domain\Model;

class SiteSectionRegionModel extends SiteSectionModel
{
    protected function prepareNewslist()
    {
        $oSweetGroup = $this->oEntryGroup;
        $oSweetGroup->parking = 'middle-middle-bottom';
        $oSweetGroup->tpl = 'news-section-list';
        $oSweetGroup->kind = 'block';

        $this->collectionsConfig['Entries'] =  array
        (
            'Collection' => '\Domain\Collection\EntryByGroup',
            'DataType' => 'Xml/Collection/EntryListByRegion',
            'Params' => array_merge
            (
                array
                (
                    'page' => @$this->INCOMING['page'],
                    'entry_kind' => array('entry', 'comment'),
                    'limit' => 15,
                    'region' => @$this->oEntryGroup->region,
                    'group' => $oSweetGroup
                )
            )
        );
    }
}