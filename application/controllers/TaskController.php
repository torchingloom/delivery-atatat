<?php

class TaskController extends \Controller_Action
{
    protected $sessiondata;

    public function init()
    {
        $this->sessiondata = new Zend_Session_Namespace('newtask');
        $this->sessiondata->step = $this->_request->getParam('step');
    }

    /**
     * @param string $what current|next|prev
     * @return int
     */
    protected function step($what = 'current')
    {
        switch ($what)
        {
            case 'current':
                return (int) $this->sessiondata->step;
            case 'next':
                return (int) $this->sessiondata->step + 1;
            case 'prev':
                return (int) $this->sessiondata->step - 1;
        }
    }


    public function newAction()
    {
    }
}


