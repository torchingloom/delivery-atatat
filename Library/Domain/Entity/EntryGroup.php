<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $status
 * @property mixed $kind
 * @property mixed $url
 * @property mixed $url_newslist
 * @property mixed $title
 * @property mixed $title_full
 * @property mixed $name
 * @property mixed $description
 * @property mixed $sort
 * @property mixed $publish_date
 *
 * @property mixed $linkNoNeed
 * @property mixed $linkTo
 * @property mixed $parking
 * @property mixed $getter
 * @property mixed $limit
 * @property mixed $tpl
 * @property mixed $commentators-names
 * @property mixed $comments-count
 */

class EntryGroup extends Entity
{
    protected static $urlprefix = '/section/';

    public static function factory(EntryGroup $o)
    {
        switch ($o->kind)
        {
            default:
            case 'section':
                $class = '\Domain\Entity\EntryGroupSection';
                if ($o->name == 'experts' && !$o->parent_id)
                {
                    $class = '\Domain\Entity\EntryGroupSectionExperts';
                }
                if ($o->name == \Service\Config::get('vars/path/default'))
                {
                    $class = '\Domain\Entity\EntryGroupSectionMain';
                }
            break;
            case 'section_region':
                $class = '\Domain\Entity\EntryGroupSectionRegion';
            break;
            case 'section_static':
                $class = '\Domain\Entity\EntryGroupSectionStatic';
            break;
            case 'block':
                $class = '\Domain\Entity\EntryGroupBlock';
            break;
            case 'thread':
                $class = '\Domain\Entity\EntryGroupThread';
            break;
            case 'advert':
                $class = '\Domain\Entity\EntryGroupAdvert';
            break;
            case 'thread':
                $class = '\Domain\Entity\EntryGroupThread';
            break;
        }

        $newo = new $class();
        $newo->fill($o->toArray());
        return $newo;
    }

    public function __construct()
    {
        $this->url = $this->linkTo ?: static::$urlprefix . "{$this->name}";
        $this->url_newslist = $this->linkTo ?: static::$urlprefix . "{$this->name}/newslist";
    }

    public function fill($array)
    {
        foreach ($array AS $key => $value)
        {
            $this->{$key} = $value;
        } 
    }

    public function __set($var, $value)
    {
        parent::__set($var, $value);
        if ($var == 'params' && $value && is_string($value))
        {
            $this->__data__ = array_merge($this->__data__, unserialize($value));
        }
    }

    /**
     * @param \Domain\Entity\Entry $oEntry
     * @return void
     */
    public function appendEntry(Entry $oEntry)
    {
        parent::appendChild('Entry', $oEntry);
    }

    /**
     * @param \Domain\Entity\EntryGroup $oEntryGroup
     * @return void
     */
    public function appendParentGroup(EntryGroup $oEntryGroup)
    {
        if ($this->getChilds('Parent'))
        {
            return;
        }
        if (!$this->linkTo)
        {
            /** @var $oParent \Domain\Entity\EntryGroup */
            $oParent = $oEntryGroup;
            $s =  $this->name;
            while ($oParent)
            {
                $s = "{$oParent->name}/{$s}";
                $oParent = $oParent->getParent();
            }
            $this->__data__['url'] = static::$urlprefix . $s;
        }
        parent::appendChild('Parent', $oEntryGroup, null);
        $oEntryGroup->appendChildGroup($this);
    }

    /**
     * @return \Domain\Entity\EntryGroup|null
     */
    public function getParent()
    {
        return $this->getChilds('Parent');
    }

    /**
     * @param \Domain\Entity\EntryGroup $oEntryGroup
     * @return void
     */
    public function appendChildGroup(EntryGroup $oEntryGroup)
    {
        parent::appendChild('Child', $oEntryGroup, 'id');
        $oEntryGroup->appendParentGroup($this);
    }

    public function getChildGroups()
    {
        return parent::getChilds('Child');
    }
}
