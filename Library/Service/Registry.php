<?php

namespace Service;

class Registry extends \Zend_Registry
{
    /**
     * @static
     * @param \Domain\Entity\EntryGroup $oEntryGroup
     * @return bool
     */
    public static function EntryGroupSelectedSet($oEntryGroup)
    {
        if ($oEntryGroup instanceof \Domain\Entity\EntryGroup)
        {
            static::set('EntryGroupSelected', $oEntryGroup);
            $aFlattenEntryGroupIds = array();
            while ($oEntryGroup)
            {
                $aFlattenEntryGroupIds[] = $oEntryGroup->id;
                $oEntryGroup = $oEntryGroup->getParent();
            }
            static::set('EntryGroupSelectedIds', $aFlattenEntryGroupIds);
            return true;
        }
        return false;
    }

    /**
     * @static
     * @return \Domain\Entity\EntryGroup $oEntryGroup|null
     */
    public static function EntryGroupSelectedGet()
    {
        return static::get('EntryGroupSelected');
    }

    /**
     * @static
     * @return array|null
     */
    public static function EntryGroupSelectedIdsGet()
    {
        $key = 'EntryGroupSelected';
        if (static::isRegistered($key))
        {
            return static::get($key);
        }
        return null;
    }

    public static function RegionSet($region)
    {
        return static::set('Region', $region);
    }

    public static function RegionGet()
    {
        $key = 'Region';
        if (static::isRegistered($key))
        {
            return static::get($key);
        }
        return null;
    }
}
 