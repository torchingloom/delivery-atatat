<?php

class UserController extends \Controller_Action
{
    public function confirmAction()
    {
        $oModel = new Domain\Model\User(array('status' => 'pending', 'activate_code' => $this->_request->getParam('code')));
        /** @var $oCollection \Domain\Collection\User */
        $oCollection = $oModel->getCollection('list');
        if (!$oCollection->activateAllChilds())
        {
            $this->_redirect('http://www.snob.ru');
        }

        $this->_helper->layout->disableLayout();
    }
}


