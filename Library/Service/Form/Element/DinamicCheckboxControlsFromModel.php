<?php

class Service_Form_Element_DinamicCheckboxControlsFromModel extends Zend_Form_Element_MultiCheckbox
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
            throw new Service_Form_Element_DinamicCheckboxControlsFromModel_Exception;
        }
    }

    public function init()
    {
        $this->checkSourceModel();
        parent::init();
        $this->optionsAdd();
    }

    public function isValid($value, $context = null)
    {
        parent::isValid($value, $context);
        return true;
    }

    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function sourceElementsGet()
    {
        return $this->_sourceElements;
    }

    public function sourceElementSelectedGet()
    {
        return $this->_sourceElements[$this->getValue()];
    }

    protected function optionsAdd()
    {
        if (!class_exists($sModelClass = "\\Domain\\Model\\{$this->_sourceModel['name']}"))
        {
            throw new Service_Form_Element_DinamicCheckboxControlsFromModel_Exception;
        }

        /* @var $oModel \Domain\Model\Model */
        $oModel = new $sModelClass (!empty($this->_sourceModel['options']) ? $this->_sourceModel['options'] : null);
        if (!($oCollection = $oModel->getCollection($this->_sourceModel['collection'])))
        {
            throw new Service_Form_Element_DinamicCheckboxControlsFromModel_Exception;
        }

        /* @var $oEntity \Domain\Entity\Entity */
        foreach ($oCollection AS $oEntity)
        {
            $this->_sourceElements[$oEntity->idGet()] = $oEntity;
            $this->options[$oEntity->idGet()] = $oEntity->toString();
        }
    }

    public function render(Zend_View_Interface $view = null)
    {
        return $this->decorate(parent::render($view));
    }

    protected function decorate($sElement)
    {
        $elname = $this->getName();
        $sElement = preg_replace('|<dd(.*?)>(.*?)</dd>|is', '<dd$1><div class="dinamic-checkbox-controls-wrapper">$2</div></dd>', $sElement);
        foreach ($this->sourceElementsGet() AS $key => $valuev)
        {
            $keyInElId = preg_replace('|[^0-9a-z]|is', '', $key);
            $sElement = preg_replace
            (
                array
                (
                    "|<label for=\"{$elname}-{$keyInElId}\">(.*?)</label>|ims",
                ),
                array
                (
                    '<div class="primary-subelement" id="primary-subelement-'. $elname .'-'. $key .'"><label for="'. $elname .'-'. $key .'">$1</label></div><div class="secondary-subelement" id="secondary-subelement-'. $elname .'-'. $key .'">'. $this->renderSubelement($valuev) .'</div>',
                ),
                $sElement
            );

            $sElement = str_replace
            (
                array
                (
                    "name=\"{$elname}[]\" id=\"{$elname}-{$keyInElId}\"",
                ),
                array
                (
                    "name=\"{$elname}[{$key}][checked]\" id=\"{$elname}-{$key}\"",
                ),
                $sElement
            );
        }
        $sElement = str_replace('<br />', '', $sElement) . self::js();
        return $sElement;
    }

    /**
     * @param $subelement Domain\Entity\UserFilter
     * @return mixed
     */
    protected function renderSubelement($subelement)
    {
        if ($subelement->kind == 'default')
        {
            return '';
        }

        if (!@class_exists($class = "\Zend_Form_Element_{$subelement->kind}"))
        {
            $class = "Service_Form_Element_{$subelement->kind}";
        }

        /** @var $o \Zend_Form_Element */
        $o = new $class($subelement->toArray());

        $o
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Errors')
            ->removeDecorator('Description')
            ->removeDecorator('Label')
        ;

        return str_replace
        (
            array
            (
                "name=\"{$subelement->name}",
                "id=\"{$subelement->name}",
                "[{$subelement->name}]",
                "[]"
            ),
            array
            (
                "name=\"{$this->getName()}[{$subelement->name}]",
                "id=\"{$this->getName()}-{$subelement->name}-sub",
                "[{$subelement->name}][value]",
                "[value][]"
            ),
            $o->render()
        );
    }



    protected static function JS()
    {
        static $js;
        if (!$js)
        {
            ob_start();
?>
<script>

$('document').ready
(
    function ()
    {
        $('.dinamic-checkbox-controls-wrapper .primary-subelement input').click
        (
            function ()
            {
                _el = $(this);
                $("#secondary-subelement-"+ _el.attr('id')).css('display', _el.attr('checked') ? 'block' : 'none');
            }
        );
    }
);

</script>

<?php
            $js = ob_get_contents();
            ob_end_clean();
            return preg_replace('/\n/ims', '', $js);
        }
    }
}

class Service_Form_Element_DinamicCheckboxControlsFromModel_Exception extends Exception
{

}