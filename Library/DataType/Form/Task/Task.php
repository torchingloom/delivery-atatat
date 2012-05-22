<?php

namespace DataType;

class Form_Task extends Form
{
    protected $stepdata;

    public function  __construct($stepdata = null)
    {
        $this->stepdata = $stepdata;
        parent::__construct();
    }
}

class Form_Task_Exception extends Form_Exception
{

}