<?php

class Service_Form_Element_DateTime extends Service_Form_Element_Date
{
    public function render(Zend_View_Interface $view = null)
    {
        $this->setAttrib('class', "{$this->getAttrib('class')} use-datetimepicker");
        return str_replace('use-datepicker', '', parent::render($view));
    }
}