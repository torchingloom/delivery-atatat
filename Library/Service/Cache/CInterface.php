<?php

namespace Service\Cache;

interface CInterface
{
    public static function get($key = null);
    public static function set($key, $val);
    public static function exists($key);
}