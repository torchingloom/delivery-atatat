<?php

namespace DataType;

class Form_UserGroup extends Form
{
    public function __construct($oGroup = null)
    {
        $cfg = new \Zend_Config_Xml(static::prefix() . 'UserGroup.xml');
        parent::__construct($cfg->form);

        /* @var $oElement \Zend_Form_Element */
        foreach ($this->getElements() AS $sElementName => $oElement)
        {
            if ($oGroup && !is_null($oGroup->{$sElementName}))
            {
                $oElement->setValue($oGroup->{$sElementName});
            }
        }

        $this->removeIdElementIfEmpty();
    }
}