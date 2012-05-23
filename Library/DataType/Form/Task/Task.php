<?php

namespace DataType;

class Form_Task extends Form
{
    protected static $stepdata;

    public function  __construct()
    {
        parent::__construct();
    }

    public static function stepdataSet($stepdata)
    {
        static::$stepdata = $stepdata;
    }

    public static function stepdataGet()
    {
        return static::$stepdata;
    }
}

class Form_Task_Exception extends Form_Exception
{

}