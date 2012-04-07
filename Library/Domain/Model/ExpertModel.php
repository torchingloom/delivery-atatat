<?php

namespace Domain\Model;

class ExpertModel extends SiteSectionModel
{
    /**
     * при поддержки 2
     *
     * @param $params
     */
    public function __construct($params)
    {
        $this->INCOMING = $params;
        $params['expertid'] = $this->INCOMING['id'];
        $params['id'] = $this->INCOMING['sectionid'];
        parent::__construct($params);
    }

    /**
     * @return \Domain\Entity\UserExpert
     */
    public function getExpert()
    {
        $a = $this->getCollection('Expert');
        return $a[$this->INCOMING['expertid']];
    }
    
    protected function prepareCollectionsConfig()
    {
        parent::prepareCollectionsConfig();
        $this->collectionsConfig['Expert'] = array
        (
            'Collection' => '\Domain\Collection\Experts',
            'DataType' => 'Xml/Collection/ExpertListWithEntries',
            'Params' => array('id' => $this->INCOMING['expertid'], 'page' => $this->INCOMING['entries-page'], 'limit' => 25)
        );
    }

}


class ExpertModelException extends EntryGroupModelException
{
}