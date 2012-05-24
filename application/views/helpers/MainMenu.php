<?php

class Zend_View_Helper_MainMenu extends \Zend_View_Helper_Abstract
{
    public function MainMenu()
    {
        static $s;
        if (!$s)
        {
            $this->view->items = $this->items();
            $s = $this->view->render('helpers/MainMenu.phtml');
        }
        return $s;
    }

    protected function items()
    {
        return array
        (
            '/task/new' => 'Поставить задачу',
            '/task/list' => 'История задач',
            '/group/list' => 'Группы пользователей',
            '/tpl/list' => 'Шаблоны',
        );
    }
}