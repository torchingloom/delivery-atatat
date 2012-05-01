<?php

namespace DataType;

class Form extends \Zend_Form
{
    public function __construct($options = null)
    {
        $this->addPrefixPath('Service_Form_Decorator', 'Service/Form/Decorator', 'decorator');
        $this->addPrefixPath('Service_Form_Element', 'Service/Form/Element', 'element');
        parent::__construct($options);
    }

    protected static function prefix()
    {
        return \Service\Config::get('includePaths.library') .'DataType/Meta/Xml/Collection/';
    }

    protected function removeIdElementIfEmpty()
    {
        if ($this->getElement('id') && !$this->getElement('id')->getValue())
        {
            $this->removeElement('id');
        }
    }
}