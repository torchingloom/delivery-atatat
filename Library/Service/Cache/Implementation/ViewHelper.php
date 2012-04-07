<?php

namespace Service\Cache;

class Implementation_ViewHelper extends Serialize
{
    public static function set($key, $val, $lifetime = null, $tags = array())
    {
		return parent::set($key, $val, $lifetime, array_merge((array) $tags, array('View_Helper')));
    }
}