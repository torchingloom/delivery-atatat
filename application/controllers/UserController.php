<?php

class UserController extends \Controller_Action
{
    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->Model = new Domain\Model\Template();
    }
}


