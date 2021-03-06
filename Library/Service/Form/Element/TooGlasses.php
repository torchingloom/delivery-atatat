<?php

class Service_Form_Element_TooGlasses extends Zend_Form_Element_Multiselect
{
    protected
        $_sourceModel,
        $_sourceModelAsync
    ;

    protected function setSourceModel($valuev)
    {
        $this->_sourceModel = $valuev;
    }

    protected function setsourceModelAsync($valuev)
    {
        $this->_sourceModelAsync = $valuev;
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
            throw new Service_Form_Element_TooGlasses_Exception;
        }
    }
    
    public function init()
    {
        $this->checkSourceModel();
        parent::init();
    }

    public function isValid($value)
    {
        return true;
    }

    public function render(Zend_View_Interface $view = null)
    {
        return $this->decorate(parent::render($view));
    }

    protected function decorate($sElement)
    {
        if (!class_exists($sModelClass = "\\Domain\\Model\\{$this->_sourceModel['name']}"))
        {
            throw new Service_Form_Element_TooGlasses_Exception;
        }

        /* @var $oModel \Domain\Model\Model */
        $oModel = new $sModelClass (!empty($this->_sourceModel['options']) ? $this->_sourceModel['options'] : null);
        /* @var $oModel \Domain\Collection\Collection */
        if (!($oCollection = $oModel->getCollection($this->_sourceModel['collection'])))
        {
            throw new Service_Form_Element_TooGlasses_Exception;
        }

        $sElement = str_replace('multiple', 'style="display: none" multiple', $sElement);
        $elname = $this->getName();
        $sElementAddictional = self::js() . $this->autocomliteJs() .'
<div class="multielement-wrapper">
<div class="multielement-form-value" id="'. $elname .'-form-value"></div>
'. $this->autocomliteField() .'
<div class="multielement-list"><ul id="'. $elname .'-source">';

        if (empty($this->_sourceModel['fillAsync']))
        {
            $i = 0;
            /* @var $oEntity \Domain\Entity\Entity */
            foreach ($oCollection AS $oEntity)
            {
                if ($this->getValue() && array_key_exists($oEntity->idGet(), $this->getValue()))
                {
                    continue;
                }
                $sElementAddictional .= "<li class='multielement-element' title='{$oEntity}'><input type='checkbox' value='{$oEntity->idGet()}' /> {$oEntity}</li>";
            }
        }

        $sElementAddictional .= '</ul></div>
<div class="multielement-control" id="multielement-control-'. $elname .'">
    <div><a href="#" class="multielement-move-left"></a></div>
    <div><a href="#" class="multielement-move-right"></a></div>
</div>
<div class="multielement-list"><ul id="'. $elname .'-result">';
        $i = 0;
        if ($this->getValue())
        {
            foreach ($this->getValue() AS $valueid => $valuetitle)
            {
                $sElementAddictional .= "<li class='multielement-element' title='{$valuetitle}'><input type='checkbox' value='{$valueid}' /> {$valuetitle}</li>";
            }
        }
        $sElementAddictional .= '</ul></div></div><div class="multielement-wrapper-after"></div>';

        return preg_replace('/<select.*?\/select>/ims', $sElementAddictional, $sElement);
    }

    protected function autocomliteField()
    {
        if ($this->_sourceModelAsync)
        {
            return '<div class="multielement-source-autocomlite"><input type="text" id="'. $this->getName() .'-source-autocomlite" /></div>';
        }
    }

    protected function autocomliteJs()
    {
        if ($this->_sourceModelAsync)
        {
            return "<script>
$().ready
(
    function()
    {
        $('#{$this->getName()}-source-autocomlite').keyup( function() { multielementSourceAutocomlite($(this), '{$this->_sourceModelAsync['url']}', '{$this->_sourceModelAsync['paramname']}') } );
        ". (!empty($this->_sourceModel['fillAsync']) ? "multielementSourceAutocomlite($('#{$this->getName()}-source-autocomlite'), '{$this->_sourceModelAsync['url']}', '{$this->_sourceModelAsync['paramname']}');" : "") ."
    }
);
</script>";
        }
    }

    protected static function JS()
    {
        static $js;
        if (!$js)
        {
            ob_start();
?>
<script>

$.ajaxSetup({  cache: true });

function multielementSourceAutocomlite(_element, _url, _paramname)
{
    $.getJSON(_url +"?"+ _paramname +"="+ _element.attr('value'), function(_result) { multielementSourceAutocomliteCallback(_element, _result) });
}

function multielementSourceAutocomliteCallback(_element, _result)
{
    _glass = $('#'+ _element.attr('id').replace(/-autocomlite/, ''));
    _glass.find('li').remove();
    _elemname = _element.attr('id').replace(/-.*/g, '');

    var items = [];
    $.each
    (
        _result,
        function(key, val)
        {
            items.push('<li class="multielement-element" title="'+ val +'"><input type="checkbox" value="'+ key +'" /> '+ val +"</li>");
        }
    );
    _glass.append(items.join(''));
    multielementClickOnRowBinder();
}

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

function multielementClickOnRowBinder()
{
    $('.multielement-element')
        .unbind('click')
        .click
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
}

function multielementFormSetValues(_form)
{
    _form.find('.multielement-form-value').each
    (
        function ()
        {
            _place = $(this);
            _elemname = $(this).attr('id').replace(/-.*/, '');
            $('#'+ _elemname +'-result').find('input')
                .clone()
                .attr('name', _elemname +'[]')
                .attr('type', 'hidden')
                .appendTo(_place)
            ;
        }
    );
    _form.unbind('submit');
    _form.submit();
}

$().ready
(
    function ()
    {
        multielementClickOnRowBinder();
        $('.multielement-control a').click
        (
            function ()
            {
                multielementMoveElement($(this).parent().parent().attr('id').replace(/.*-/g, ''), $(this).attr('class').replace(/.*-/g, ''));
                return false;
            }
        );

        $('.multielement-control').parents('form').submit( function() { multielementFormSetValues($(this)); return false; } );
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

class Service_Form_Element_TooGlasses_Exception extends Exception
{

}