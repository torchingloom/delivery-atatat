<?php

class IndexController extends \Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
    }

    public function testAction()
    {
echo 123;
    }
}


