<?php

class TplController extends \Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/tpl/list');
    }

    public function listAction()
    {
        $this->view->oModel = new Domain\Model\Template();
    }

    public function viewAction()
    {
        $oModel = new Domain\Model\Template(array('id' => $this->_request->getParam('id')));
        $oCollection = $oModel->getCollection('list');
        if (!($this->view->oTpl = $oCollection->current()))
        {
            $this->_redirect('/error/tplnotfound');
        }
    }
}


