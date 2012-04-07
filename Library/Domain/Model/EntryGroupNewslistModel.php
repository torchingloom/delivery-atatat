<?php

namespace Domain\Model;

class EntryGroupNewslistModel extends SiteSectionModel
{
    public function __construct($params)
    {
        if ($params['region'])
        {
            $this->region = $params['region'];
            unset($params['region']);
        }
        parent::__construct($params);
    }
}