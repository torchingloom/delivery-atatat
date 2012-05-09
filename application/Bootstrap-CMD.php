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

    protected function _initDbSnob()
    {
        $_params = \Service\Config::get('database/snob/params')->toArray();

        // пока что прям так
        $db = new \DataSource\Mysql\Mysql
        (
            new \DataSource\Mysql\Adapter
            (
                array
                (
                    'host'     => $_params['host'],
                    'username' => $_params['username'],
                    'password' => $_params['password'],
                    'dbname'   => $_params['dbname'],
                    'charset'  => 'utf8'
                )
            )
        );

        \Service\Registry::set('db_snob', $db);
    }
}
