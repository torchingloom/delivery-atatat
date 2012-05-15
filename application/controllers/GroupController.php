<?php

class GroupController extends \Controller_Action
{
    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->view->oModel = new Domain\Model\UserGroup();
    }

    public function viewAction()
    {
        $this->view->oModel = new Domain\Model\UserGroup();
    }
}


