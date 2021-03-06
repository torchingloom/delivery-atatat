<?php

namespace DataType;

class Form extends \Zend_Form
{
    protected $xmlname = null;

    /**
     * @param \Domain\Entity\Entity $o
     */
    public function __construct($o = null)
    {
        $this->addPrefixPath('Service_Form_Decorator', 'Service/Form/Decorator', 'decorator');
        $this->addPrefixPath('Service_Form_Element', 'Service/Form/Element', 'element');

        $cfg = new \Zend_Config_Xml(static::prefix() . $this->xmlname .'.xml');
        parent::__construct($cfg->form);

        $this->valuesSet($o);

        $this->removeIdElementIfEmpty();
    }

    /**
     * @param \Domain\Entity\Entity $o
     */
    public function valuesSet($o)
    {
        if ($o)
        {
            /* @var $oElement \Zend_Form_Element */
            foreach ($this->getElements() AS $sElementName => $oElement)
            {
                if ($childAsField = $oElement->getAttrib('childAsField'))
                {
                    if ($childs = $o->getChilds($childAsField))
                    {
                        $valuev = array();
                        /* @var \Domain\Entity\Entity $child */
                        foreach ($childs AS $child)
                        {
                            $valuev[$child->idGet()] = $child->toString();
                        }
                        $oElement->setValue($valuev);
                        continue;
                    }
                }
                if (@$o->{$sElementName})
                {
                    $oElement->setValue($o->{$sElementName});
                }
            }
        }
    }

    public function messageShow($ident)
    {
        $messages = $this->getAttrib('messages');
        if (!empty($messages[$ident]))
        {
            $vals = $this->getElements();
            foreach ($vals AS $key => &$valuev)
            {
                $valuev = $valuev->getValue();
            }
            $msg = \Utils::sprintf($messages[$ident], $vals);
            $this->addDecorator('Message', array('message' => $msg, 'kind' => 'good'));
        }
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

class Form_Exception extends \Exception
{}