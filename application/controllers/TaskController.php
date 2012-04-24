<?php

class TaskController extends \Controller_Action
{
    public function indexAction()
    {
    }

    public function createAction()
    {
    }

    public function listAction()
    {
        $this->Model = new Domain\Model\Template();
    }
}


