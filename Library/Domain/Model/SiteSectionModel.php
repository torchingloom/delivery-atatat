<?php

namespace Domain\Model;

class SiteSectionModel extends Model
{
    /**
     * @var \Domain\Entity\EntryGroup
     */
    protected $oEntryGroup;
    protected
        $INCOMING,
        $EntryGroupId,
        $NestedEntryGroupList
    ;

    public function __construct($params)
    {
        $this->INCOMING = $params;
        $this->prepareCollectionsConfig();
        parent::__construct();
    }

    /**
     * @return \Domain\Entity\EntryGroup
     */
    public function getEntryGroup()
    {
        return $this->oEntryGroup;
    }

    public function getEntryListCollection()
    {
        return $this->getCollection('Entries');
    }
    
    protected function prepareCollectionsConfig()
    {
        if (!empty($this->INCOMING['id']))
        {
            $this->EntryGroupId = $this->INCOMING['id'];
        }
        if (!empty($this->INCOMING['path']))
        {
            $this->EntryGroupId = static::getEntryGroupIdByPath($this->INCOMING['path']);
        }
        if (!$this->EntryGroupId)
        {
//            throw new EntryGroupModelException("Группа не задана!");
            $this->EntryGroupId = static::getEntryGroupIdByPath(\Service\Config::get('vars/path/default'));
        }

        $this->oEntryGroup = static::getEntryGroupById($this->EntryGroupId);
        $this->NestedEntryGroupList = static::getNestedEntryGroupByParentId($this->EntryGroupId);

        /** @var $group \Domain\Entity\EntryGroup */
        foreach ($this->NestedEntryGroupList AS &$group)
        {
            $group->appendParentGroup($this->oEntryGroup);
            $this->collectionsConfig[$group->id] = $this->getCollectionItemConfigByGroupData($group);
        }

        $this->prepareNewslist();
    }

    protected function prepareNewslist()
    {
        if ($this->NestedEntryGroupList)
        {
            if ($this->oEntryGroup instanceof \Domain\Entity\EntryGroupSectionMain)
            {
//                return;
            }

            $oSweetGroup = $this->oEntryGroup;
            $oSweetGroup->parking = 'middle-middle-bottom';
            $oSweetGroup->tpl = 'news-section-list';
            $oSweetGroup->kind = 'block';

            $ids = array($this->EntryGroupId);
            if ($aChildrenSection = \Service\Registry::get('db')->EntryGroupListByParentId($oSweetGroup->id, 'section'))
            {
               $ids = array_merge(array_keys($aChildrenSection), $ids);
            }


            $this->collectionsConfig['Entries'] =  array
            (
                'Collection' => '\Domain\Collection\EntryByGroup',
                'DataType' => 'Xml/Collection/'. ($this->oEntryGroup instanceof \Domain\Entity\EntryGroupSectionMain ? 'EntryListAllWithAuthor' : 'EntryListWithAuthorByGroup'),
                'Params' => array_merge
                (
                    $oSweetGroup->toArray(),
                    array
                    (
                        'select-type' => 'onelist',
                        'group' => $oSweetGroup,
                        'id' => $ids,
                        'page' => @$this->INCOMING['page'],
                        'entry_kind' => 'entry',
                        'limit' => 15,
                        'region' => @$this->region
                    )
                )
            );
        }
    }

    protected function getCollectionItemConfigByGroupData($oGroup)
    {
        $cfg = array
        (
            'Collection' => '\Domain\Collection\EntryByGroup',
            'DataType' => 'Xml/Collection/EntryListByGroup',
            'Params' => array_merge
            (
                $this->INCOMING,
                $oGroup->toArray(),
                array('group' => $oGroup, 'entry_to_group_kind' => 'display')
            )
        );
        
        // sorry
        switch ($oGroup->getter)
        {
            case 'ThreadList':
                $cfg['Collection'] = '\Domain\Collection\EntryGroupWithEntriesByParent';
                $cfg['DataType'] = 'Xml/Collection/EntryGroupListWithEntriesByParent';
                $cfg['Params']['entry_group_kind'] = 'thread';
            break;
            case 'EntryListWithAuthor':
                $cfg['DataType'] = 'Xml/Collection/EntryListWithAuthorByGroup';
            break;
            case 'EntryListWithCommentators':
                $cfg['DataType'] = 'Xml/Collection/EntryListWithCommentatorsByGroup';
            break;
        }

        $cfg['Params']['page'] = null;

        return $cfg;
    }
    
    static protected function getEntryGroupIdByPath($sPath)
    {
        return \Service\Registry::get('db')->EntryGroupIdByPath($sPath);
    }

    static protected function getNestedEntryGroupByParentId($iParentId)
    {
        /**
         * Магия наследования блоков тоже тут!!!
         * EntryGroupListByParentIdScientific
         */
        return \Service\Registry::get('db')->BlockListBySectionIdScientific($iParentId);
    }

    static protected function getEntryGroupById($id)
    {
        return \Service\Registry::get('db')->EntryGroupWithParentById($id);
    }
}

class EntryGroupModelException extends \Exception
{
}