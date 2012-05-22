<?php

class Service_Form_Element_SelectFromModel extends Zend_Form_Element_Select
{
    protected
        $sourceModel
    ;

    protected function setSourceModel($valuev)
    {
        $this->sourceModel = $valuev;
    }

    protected function checkSourceModel()
    {
        if
        (
            !$this->sourceModel
            || !is_array($this->sourceModel)
            || empty($this->sourceModel['name'])
            || empty($this->sourceModel['collection'])
        )
        {
            throw new Service_Form_Element_SelectFromModel_Exception;
        }
    }
    
    public function init()
    {
        $this->checkSourceModel();
        parent::init();
        $this->optionsAdd();
    }

    protected function optionsAdd()
    {
        if (!class_exists($sModelClass = "\\Domain\\Model\\{$this->sourceModel['name']}"))
        {
            throw new Service_Form_Element_SelectFromModel_Exception;
        }
        /* @var $oModel \Domain\Model\Model */
        $oModel = new $sModelClass (!empty($this->sourceModel['options']) ? $this->sourceModel['options'] : null);
        /* @var $oModel \Domain\Collection\Collection */
        if (!($oCollection = $oModel->getCollection($this->sourceModel['collection'])))
        {
            throw new Service_Form_Element_SelectFromModel_Exception;
        }

        /* @var $oEntity \Domain\Entity\Entity */
        foreach ($oCollection AS $oEntity)
        {
            $this->options[$oEntity->idGet()] = $oEntity->__toString();
        }
    }
}

class Service_Form_Element_SelectFromModel_Exception extends Exception
{

}