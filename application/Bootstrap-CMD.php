<?php

class BootstrapCmd extends Bootstrap
{
    protected function _initFrontcontroller()
    {
        return $this->FromtController('cmd');
    }

    protected function _initRequest($uri = null)
    {
        $INCOMING = $this->getApplication()->getOption('ARGS');
        parent::_initRequest(Zend_Uri_Http::fromString("http://fuhrer/index/{$INCOMING[0]}"));
    }
}
