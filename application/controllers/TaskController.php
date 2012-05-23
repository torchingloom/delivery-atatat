<?php

class TaskController extends \Controller_Action
{
    protected
        $sessiondata,
        $steps = 3
    ;

    public function init()
    {
        parent::init();
        $this->sessiondata = new \Zend_Session_Namespace('newtask');
        $this->sessiondata->step = $this->_request->getParam('step');
        if (!$this->stepIsValid($this->sessiondata->step))
        {
            $this->_redirect('/task/new');
        }
    }

    /**
     * @param string $what current|next|prev
     * @param int $step
     * @return int
     */
    protected function step($what = 'current', $step = 0)
    {
        $step = $step ?: (int) $this->sessiondata->step;
        switch ($what)
        {
            case 'next':
                $step = $step + 1 > $this->steps ? null : $step + 1;
                break;
            case 'prev':
                $step = $step - 1 < 0 ? null : $step - 1;
                break;
        }
        return (int) $step;
    }

    protected function stepData($step = null)
    {
        if ($step === null)
        {
            $rs = array();
            if (!$this->sessiondata->data)
            {
                return;
            }
            foreach ($this->sessiondata->data AS $step => $data)
            {
                $rs[$step] = unserialize($data);
            }
            return $rs;
        }

        if (array_key_exists($step, $this->sessiondata->data))
        {
            return unserialize($this->sessiondata->data[$step]);
        }
        return null;
    }

    protected function stepDataSet($step, $data)
    {
        $this->sessiondata->data[$step] = serialize($data);
    }

    protected function stepIsValid($step)
    {
        if (!$step)
        {
            return true;
        }
        return null !== $this->stepData($this->step('prev', $step));
    }

    public function newAction()
    {
//        if (!($oForm = $this->stepData($this->step())))
        {
            $class = "\\DataType\\Form_Task_Step{$this->step()}";
            /* @var $oForm \DataType\Form_Task */
            $oForm = new $class($this->stepData());
        }

        if ($_POST && $oForm->isValid($_POST))
        {
            $this->stepDataSet($this->step(), $oForm);
            if ($this->step('next'))
            {
                if ($nextstep = $this->step('next'))
                {
                    $this->_redirect("/task/new/{$nextstep}");
                }
            }
            else
            {
                $this->finalize();
            }
        }

        $this->view->oForm = $oForm;
    }

    protected function finalize()
    {
        \Utils::printr($this->stepData(3)->when_start->getValue()); exit();
    }
}


