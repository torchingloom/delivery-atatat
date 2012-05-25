<?php

namespace DataType;

class Form_Task_Step3 extends Form_Task
{
    protected $xmlname = 'Task/Step3';

    public function valuesSet()
    {
        $o = new \stdClass();
        $o->name = ''. date('d.m.Y H:i') .' '. current(static::$stepdata[0]->template->sourceElementSelectedGet())->name;
        // todo жирно, да?
        parent::valuesSet($o);
    }
}
