<?php

class Service_Form_Element_TinyMCE extends Zend_Form_Element_Textarea
{
    public function render(Zend_View_Interface $view = null)
    {
        $this->getView()->headScript()
            ->appendFile("/js/tiny_mce/jquery.tinymce.js", 'text/javascript')
            ->appendFile("/js/tiny_mce/init.js", 'text/javascript')
        ;
        $this->setAttrib('class', 'tinymce');
        return parent::render($view);
    }
}