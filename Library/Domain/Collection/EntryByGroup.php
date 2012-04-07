<?php

namespace Domain\Collection;

class EntryByGroup extends Entry implements ByGroup
{
    /**
     * @var EntryGroup
     */
    protected $oGroup;
    
    public function __construct($params = array())
    {
        if (!isset($params['Params']['group']))
        {
            throw new CollectionEntryByGroupException('Group is empty');
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

    /**
     * @return EntryGroup
     */
    public function getGroup()
    {
        return $this->oGroup;
    }
}


class CollectionEntryByGroupException extends CollectionException
{
}