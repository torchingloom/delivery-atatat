<?php

class JController extends \Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
    }

    public function formExampleAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/html');

        if ($_POST)
        {
            $ch = curl_init();
            curl_setopt_array
            (
                $ch,
                array
                (
                    CURLOPT_URL => "http://{$_SERVER['HTTP_HOST']}/j/subscribe",
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => "name={$this->_request->getParam('name')}&email={$this->_request->getParam('email')}",
                    CURLOPT_RETURNTRANSFER => 1,
                )
            );
            $response = curl_exec($ch);
            $response = @json_decode($response);
            \Utils::printr($response);
            curl_close($ch);
        }

        echo $this->view->render('subscribe-form.tpl');
    }

    public function subscribeAction()
    {
        $oModel = new Domain\Model\User(array('isnew' => 1));
        $oCollection = $oModel->getCollection('list');
        if ($rs = $oCollection->store(array(array('first_name' => $this->_request->getParam('name'), 'email' => $this->_request->getParam('email')))))
        {
            foreach ($rs AS &$w)
            {
                foreach ($w AS &$u)
                {
                    $u = $u->toArray();
                }
            }
        }
        echo $this->view->json($rs);
    }

    public function usersAction()
    {
        $oModel = new Domain\Model\User(array('query' => $this->_request->getParam('q'), 'status' => 'normal'));
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


