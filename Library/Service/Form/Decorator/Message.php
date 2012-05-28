<?php

class Service_Form_Decorator_Message extends Service_Form_Decorator_Decorator
{
    public function render($content)
    {
        $content = parent::render($content);
        return preg_replace("/(<form.*?>)/ims", "{$this->msg()}\$1", $content);
    }

    protected function msg()
    {
        $kind = $this->getOption('kind') ?: 'good';
        return "<div class='form-message form-message-{$kind}'>{$this->getOption('message')}</div>";
    }
}