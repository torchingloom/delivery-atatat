<?php

namespace Controller;

class ViewFuhrer
{
    public static function apply($script, $vars = array(), $folder = null)
    {
        if (!$folder)
        {
            $folder = APPLICATION_PATH .'/views/scripts';
        }

        $oFuhrer = new \Zend_View();
        foreach ($vars AS $var => $val)
        {
            $oFuhrer->assign($var, $val);
        }

        $oFuhrer->setScriptPath($folder);
        $_render = $oFuhrer->render($script);
        return $_render;
    }
}