<?php

class GroupController extends \Controller_Action
{
    public function indexAction()
    {
    }

    public function listAction()
    {
//        $this->view->oModel = new Domain\Model\UserGroup(array('without_childs' => array('user')));
        $this->view->oModel = new Domain\Model\UserGroupCategory();
    }
    
    public function rmAction()
    {
        $oModel = new Domain\Model\UserGroup(array('id' => $id = $this->_request->getParam('id')));
        $oCollection = $oModel->getCollection('list');
        /* @var $oGroup Domain\Entity\UserGroup */
        if (!($oGroup = $oCollection->current()))
        {
            $this->_redirect('/error/usergroupnotfound');
        }
        $oCollection->remove(array($id));
        $this->_redirect('/group/list');
    }

    public function viewAction()
    {
        $isNew = $this->_request->getParam('new');

        $oModel = new Domain\Model\UserGroup(array('id' => $this->_request->getParam('id')));
        $oCollection = $oModel->getCollection('list');
        /* @var $oGroup Domain\Entity\UserGroup */
        if (!($oGroup = $oCollection->current()) && !$isNew)
        {
            $this->_redirect('/error/usergroupnotfound');
        }

        $oForm = new DataType\Form_UserGroup($oGroup);

        $this->view->oGroup = $oGroup;
        $this->view->oForm = $oForm;
        if ($_POST && $oForm->isValid($_POST))
        {
            unset($_POST['submit']);
            if ($result = $oCollection->store(array($_POST)))
            {
                $oForm->messageShow('save');
                if ($isNew)
                {
                    $this->_redirect('/group/'. current(current($result)));
                }
            }
        }
    }
}


