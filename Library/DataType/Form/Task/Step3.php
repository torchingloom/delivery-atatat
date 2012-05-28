<?php

namespace DataType;

class Form_Task_Step3 extends Form_Task
{
    protected $xmlname = 'Task/Step3';

    public function valuesSet()
    {
        $o = new \stdClass();
        $o->name = ''. date('d.m.Y H:i') .' '. current(static::$stepdata[0]->template->sourceElementSelectedGet())->name;
        $o->when_start = date('d.m.Y H:i', strtotime('+10 min'));
        // todo жирно, да?
        parent::valuesSet($o);
    }

    public function testitResultSet($rs)
    {
        $oFld = $this->getElement('testemail');
        $oFld->setErrors(array($oFld->getAttrib('sendit-msg')));
    }
}
