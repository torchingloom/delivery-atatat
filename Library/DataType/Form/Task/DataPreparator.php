<?php

namespace DataType;

class Form_Task_DataPreparator
{
    public static function prepare($incoming)
    {
        $data = array();
        /** @var $oStep \Zend_Form */
        foreach ($incoming AS $oStep)
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
