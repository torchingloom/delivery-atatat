<?php

namespace Domain\Model;

class EntryModel extends SiteSectionModel
{
    /**
     * @var \Domain\Entity\Entry
     */
    protected $Entry;

    /**
     * при поддержки 2
     *
     * @param $params
     */
    public function __construct($params)
    {
        $this->INCOMING = $params;
        $this->prepare();
        $params['id'] = $this->getEntry()->group_id;
        parent::__construct($params);
    }


    protected function prepareCollectionsConfig()
    {
        parent::prepareCollectionsConfig();
        $this->collectionsConfig['Comments'] = array
        (
            'Collection' => '\Domain\Collection\Entry',
            'DataType' => 'Xml/Collection/EntryListWithAuthorAndBodyByParentEntry',
            'Params' => array_merge($this->INCOMING, array('id' => $this->getEntry()->id, 'limit' => 10/*, 'page' => $this->INCOMING['page']*/))
        );

    }

    public function getEntry()
    {
        return $this->Entry;
    }

    protected function prepare()
    {

        if (empty($this->INCOMING['id']))
        {
            throw new EntryModelException("Энтри не задана");
        }
        if (!($this->Entry = \Service\Registry::get('db')->EntryById($this->INCOMING['id'])))
        {
            throw new EntryModelException("Энтри задана плохо");
        }
        $this->Entry->appendThread(\Service\Registry::get('db')->EntryThreadList(array('id' => $this->Entry->id)));
        $this->Entry->appendResources(\Service\Registry::get('db')->ResourceByEntry(array('id' => $this->Entry->id, 'type' => array('entry_body' => 0))));
        $this->Entry->appendAuthor(array_shift(\Service\Registry::get('db')->EntryAuthorList(array('id' => $this->Entry->id))));
    }

}


class EntryModelException extends EntryGroupModelException
{
}