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

    public function rmAction()
    {
        $oModel = new Domain\Model\Template(array('id' => $id = $this->_request->getParam('id')));
        $oCollection = $oModel->getCollection('list');
        /* @var $oTpl Domain\Entity\Template */
        if (!($oTpl = $oCollection->current()))
        {
            $this->_redirect('/error/tplnotfound');
        }

        $oCollection->remove(array($id));
        $this->_redirect('/tpl/list');
    }

    public function viewAction()
    {
        $isNew = $this->_request->getParam('new');

        $oModel = new Domain\Model\Template(array('id' => $this->_request->getParam('id')));
        $oCollection = $oModel->getCollection('list');
        /* @var $oTpl Domain\Entity\Template */
        if (!($oTpl = $oCollection->current()) && !$isNew)
        {
            $this->_redirect('/error/tplnotfound');
        }

        $oForm = new DataType\Form_Template($oTpl);

        $this->view->oTpl = $oTpl;
        $this->view->oForm = $oForm;

        if ($_POST && $oForm->isValid($_POST))
        {
            unset($_POST['submit']);
            if ($result = $oCollection->store(array($_POST)))
            {
                $oForm->messageShow('save');
                if ($isNew)
                {
                    $this->_redirect('/tpl/'. current(current($result)));
                }
            }
        }
    }
}


