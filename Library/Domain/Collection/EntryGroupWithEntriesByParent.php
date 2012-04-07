<?php

namespace Domain\Collection;

class EntryGroupWithEntriesByParent extends EntryGroup implements ByGroup
{
    /**
     * @var EntryGroup
     */
    protected $oGroup;
    
    public function __construct($params = array())
    {
        if (!isset($params['Params']['group']))
        {
            throw new EntryGroupWithEntriesByParentException('Group is empty');
        }
        else
        {
            $this->oGroup = $params['Params']['group'];
            $params['Params']['group'] = $params['Params']['group']->toArray();
        }

        $params['Params'] = array_merge($params['Params']['group'], $params['Params']);
        unset($params['Params']['group']);

        parent::__construct($params);
    }

    public function getGroup()
    {
        return $this->oGroup;
    }
}


class EntryGroupWithEntriesByParentException extends CollectionException
{
}