<?php

namespace DataType;

class Form_Task_DataPreparator
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function prepare()
    {
        $data = array();
        /** @var $oStep \Zend_Form */
        foreach ($this->data AS $oStep)
        {
            /** @var $oElement \Zend_Form_Element */
            foreach ($oStep->getElements() AS $oElement)
            {
                if (in_array($oElement->getType(), array('Zend_Form_Element_Submit', 'Zend_Form_Element_Reset')))
                {
                    continue;
                }
                $data[$oElement->getName()] = $oElement->getValue();
            }
        }
        return $data;
    }
}
