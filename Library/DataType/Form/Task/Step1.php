<?php

namespace DataType;

class Form_Task_Step1 extends Form_Task
{
    protected $xmlname = 'Task/Step1';

    public function valuesSet()
    {
        /* @var $oFormStep0 Form_Task_Step0 */
        if (!(($oFormStep0 = static::$stepdata[0]) instanceof Form_Task_Step0))
        {
            throw new Form_Task_Exception('Шаг 0 не пройден');
        }
        parent::valuesSet(current($oFormStep0->template->sourceElementSelectedGet()));
    }
}
