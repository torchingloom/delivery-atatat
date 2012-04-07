<?php

namespace Service\Cache;

class TagCollection
{
    private static $tags = array();
    
    public static function apped($tags)
    {
        foreach ((array) $tags AS $tag)
        {
            if (strpos($tag, 'Entry') === 0 || strpos($tag, 'Zend_View') === 0 || strpos($tag, 'PageBlockTypeId') === 0)
            {
                self::$tags[] = $tag;
            }
        }
    }

    public static function get()
    {
        return array_unique(self::$tags);
    }
}