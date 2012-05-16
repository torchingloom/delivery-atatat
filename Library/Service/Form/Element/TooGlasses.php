<?php

class Service_Form_Element_TooGlasses extends Zend_Form_Element_Multiselect
{
    protected $sourceModel;

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
            throw new Service_Form_Element_TooGlasses_Exception;
        }
    }
    
    public function init()
    {
        $this->checkSourceModel();
        parent::init();
    }

    public function render(Zend_View_Interface $view = null)
    {
        return $this->decorate(parent::render($view));
    }

    protected function decorate($sElement)
    {
        if (!class_exists($sModelClass = "\\Domain\\Model\\{$this->sourceModel['name']}"))
        {
            throw new Service_Form_Element_TooGlasses_Exception;
        }

        /* @var $oModel \Domain\Model\Model */
        $oModel = new $sModelClass (!empty($this->sourceModel['options']) ? $this->sourceModel['options'] : null);
        /* @var $oModel \Domain\Collection\Collection */
        if (!($oCollection = $oModel->getCollection($this->sourceModel['collection'])))
        {
            throw new Service_Form_Element_TooGlasses_Exception;
        }

        $sElement = str_replace('multiple', 'style="display: none" multiple', $sElement);
        $elname = $this->getName();
        $sElementAddictional = '
<div class="multielement-wrapper">
<div class="multielement-list"><ul id="'. $elname .'-source">';
        $i = 0;
        /* @var $oEntity \Domain\Entity\Entity */
        foreach ($oCollection AS $oEntity)
        {
            $sElementAddictional .= "<li id='{$elname}-item-{$oEntity->idGet()}' class='multielement-element' title='{$oEntity}'><input type='checkbox' value='{$oEntity->idGet()}-source-item' /> {$oEntity}</li>";
        }
        $sElementAddictional .= '</ul></div>
<div class="multielement-control" id="multielement-control-'. $elname .'">
    <div><a href="#" class="multielement-move-left"></a></div>
    <div><a href="#" class="multielement-move-right"></a></div>
</div>
<div class="multielement-list"><ul id="'. $elname .'-result">';
        $i = 0;
        /* @var $oEntity \Domain\Entity\Entity */
        foreach ($oCollection AS $oEntity)
        {
        }
        $sElementAddictional .= '</ul></div></div><div class="multielement-wrapper-after"></div>';

        return str_replace('</select>', "</select>". self::js() . $sElementAddictional, $sElement);
    }

    protected static function JS()
    {
        static $js;
        if (!$js)
        {
            ob_start();
?>
<script>

function multielementMoveElement(_element, _direction)
{
    _source = _element +'-result';
    _destination = _element +'-source';
    if (_direction == 'right')
    {
        _source = _element +'-source';
        _destination = _element +'-result';
    }
    _source = $('#'+ _source);
    _destination = $('#'+ _destination);

    _source.find('input[checked=checked]')
        .removeAttr('checked')
        .parent().removeClass('selected').appendTo(_destination)
    ;
}

$('document').ready
(
    function ()
    {
    	$('.multielement-element').click
        (
            function ()
            {
                inpt = $(this).find("input");
                inpt.attr("checked", !inpt.attr("checked"));
                $(this).removeClass('selected');
                if (inpt.attr("checked"))
                {
                    $(this).addClass('selected');
                }
                return false;
            }
        );

        $('.multielement-control a').click
        (
            function ()
            {
                multielementMoveElement($(this).parent().parent().attr('id').replace(/.*-/g, ''), $(this).attr('class').replace(/.*-/g, ''));
                return false;
            }
        );
    }
);
</script>
<?php
            $js = ob_get_contents();
            ob_end_clean();
            return $js;
        }
    }
}

class Service_Form_Element_TooGlasses_Exception extends Exception
{

}