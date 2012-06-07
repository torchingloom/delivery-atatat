<?php

class Service_Form_Element_SelectFromModel extends Zend_Form_Element_Select
{
    protected
        $_sourceModel,
        $_sourceElements
    ;

    protected function setSourceModel($valuev)
    {
        $this->_sourceModel = $valuev;
    }

    protected function checkSourceModel()
    {
        if
        (
            !$this->_sourceModel
            || !is_array($this->_sourceModel)
            || empty($this->_sourceModel['name'])
            || empty($this->_sourceModel['collection'])
        )
        {
            throw new Service_Form_Element_SelectFromModel_Exception('Source model not set');
        }
    }
    
    public function init()
    {
        $this->checkSourceModel();
        parent::init();
        $this->optionsAdd();
    }

    public function sourceElementsGet()
    {
        return $this->_sourceElements;
    }

    public function sourceElementSelectedGet()
    {
        $result = null;
        if ($this->getValue())
        {
            $result = array();
            foreach ((array) $this->getValue() AS $valuev)
            {
                $result[] = $this->_sourceElements[$valuev];
            }
        }
        return $result;
    }

    protected function optionsAdd()
    {
        if (!class_exists($sModelClass = "\\Domain\\Model\\{$this->_sourceModel['name']}"))
        {
            throw new Service_Form_Element_SelectFromModel_Exception('Source model class not found');
        }
        /* @var $oModel \Domain\Model\Model */
        $oModel = new $sModelClass (!empty($this->_sourceModel['options']) ? $this->_sourceModel['options'] : null);
        if (!($oCollection = $oModel->getCollection($this->_sourceModel['collection'])))
        {
            throw new Service_Form_Element_SelectFromModel_Exception;
        }

        /* @var $oEntity \Domain\Entity\Entity */
        foreach ($oCollection AS $oEntity)
        {
            $this->_sourceElements[$oEntity->idGet()] = $oEntity;
            $this->options[$oEntity->idGet()] = $oEntity->toString();
        }
    }
}

class Service_Form_Element_SelectFromModel_Exception extends Exception
{

}