<?php

namespace Service;

class CmdHttp
{
    const SECRET_WORD = 'dfajhsdfanwernmweruixczxcz';

    public function __construct()
    {

    }

    public function run($vars)
    {
        $host = \Service\Config::get('cmdhttp.host');
        $path = strtolower(join('-', $vars));
        return file_get_contents("http://{$host}/cmd/{$path}?token=". self::secretGet());
    }

    public static function secretGet()
    {
        return md5(self::SECRET_WORD . date('dmyh'));
    }

    public static function secretIsValid($secret)
    {
        return GODMODE || self::secretGet() == $secret;
    }
}
