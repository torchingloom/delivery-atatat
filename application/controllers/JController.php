<?php

class JController extends \Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
    }

    public function usersAction()
    {
        $oModel = new Domain\Model\User(array('query' => $this->_request->getParam('q')));
        /* @var $oModel Domain\Collection\User */
        $oList = $oModel->getCollection('list');
        $r = array();
        foreach ($oList AS $oUser)
        {
            $r[$oUser->idGet()] = $oUser->__toString();
        }
        echo $this->view->json($r);
    }
}


