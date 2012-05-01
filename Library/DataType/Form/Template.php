<?php

namespace DataType;

class Form_Template extends Form
{
    public function __construct($oTpl = null)
    {
        $cfg = new \Zend_Config_Xml(static::prefix() . 'Template.xml');
        parent::__construct($cfg->form);

        /* @var $oElement \Zend_Form_Element */
        foreach ($this->getElements() AS $sElementName => $oElement)
        {
            if ($oTpl && !is_null($oTpl->{$sElementName}))
            {
                $oElement->setValue($oTpl->{$sElementName});
            }
        }

        $this->removeIdElementIfEmpty();
    }
}