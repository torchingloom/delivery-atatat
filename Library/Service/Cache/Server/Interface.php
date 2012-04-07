<?php

namespace Service\Cache;

interface Server_Interface
{
    public static function get($key);
    public static function set($key, $val);
    public static function exists($key);
    public static function clean($key);/*
    public static function cleanByTags($tags);
    protected static function logit($what, $key);*/
}