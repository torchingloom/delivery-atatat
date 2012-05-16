<?php

class Controller_Action extends \Zend_Controller_Action
{
    public function init()
    {
        $this->view->headTitle(\Service\Config::get('html.title_prefix'));
        $this->view->headScript()
            ->appendFile("/js/libs/jquery-1.7.2.min.js", 'text/javascript')
            ->appendFile("/js/script.js", 'text/javascript')
        ;

        $this->view->headLink()->prependStylesheet("/css/style.css");

        $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
    }
}

