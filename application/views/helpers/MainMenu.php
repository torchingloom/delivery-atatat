<?php

class Zend_View_Helper_Menu extends Zend_View_Helper_Navigation
{
    public function Menu()
    {
        return $this;
    }

    public function init($pagename)
    {
        Zend_Registry::set('Zend_Navigation', $container = new Zend_Navigation(static::pages($pagename)));
        $this->setContainer($container);
    }

    protected static function pages($pagename)
    {
        \Service\Utils::printr(\Service\Config::get('menu')); exit();
    }
}