<?php

class IndexController extends \Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
    }

    public function gropsAutofillAction()
    {
        $oModel = new \Domain\Model\UserGroup(array('algo' => 'isNotNull'));
        /* @var $oCollection \Domain\Collection\UserGroup */
        $oCollection = $oModel->getCollection('list');
        $oCollection->autofill();
    }
}


