<?php

class Service_Form_Element_Date extends Zend_Form_Element_Text
{
    public function render(Zend_View_Interface $view = null)
    {
        $this->getView()->headScript()
            ->appendFile("/js/libs/jquery/ui/jquery-ui-1.8.20.custom.min.js", 'text/javascript')
            ->appendFile("/js/libs/jquery/ui/jquery-ui-timepicker-addon.js", 'text/javascript')
            ->appendFile("/js/libs/jquery/ui/init.js", 'text/javascript')
        ;

        $this->getView()->headLink()
            ->appendStylesheet("/js/libs/jquery/ui/themes/base/jquery.ui.all.css")
            ->appendStylesheet("/js/libs/jquery/ui/themes/base/jquery.ui.datepicker.css")
        ;

        $this->setAttrib('class', "{$this->getAttrib('class')} use-datepicker");

        return parent::render($view);
    }
}