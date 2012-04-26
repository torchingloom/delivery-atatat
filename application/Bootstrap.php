<?php

use \Autoloader;
use \Service\Auth\Auth;
use \Service\Auth\Roles;
use \Controller\Router;
use \DataSource\Mysql\Mysql;



if (isset($_REQUEST["LOGIT"]))
{
    define('LOGIT', true);
}
defined('LOGIT') || define('LOGIT', false);



class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    protected function _initTrasgalacticUtils()
    {
    }

    protected function _initAutoload()
    {
        require_once '../Library/Autoloader.php';
        \Zend_Loader_Autoloader::getInstance()->registerNamespace('Main')->pushAutoloader(new Autoloader());
    }

    protected function _initConfig()
    {
        \Service\Config::init($this->getOptions());
    }
/*
    protected function _initForceSession()
    {
        $this->bootstrap('db')->bootstrap('session');
        $defaultNamespace = new \Zend_Session_Namespace('Default');
        $defaultNamespace->numberOfPageRequests++;
    }
*/
    public function _initFrontcontroller()
    {
        $controller = \Zend_Controller_Front::getInstance();
        $controller->throwExceptions('resources.frontController.params.displayExceptions');
        $controller->setControllerDirectory( \Service\Config::get('resources.frontController.controllerDirectory') );
        return $controller;
    }

    protected function _initRequest()
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');

        $request = new \Controller_Request();
        $request->setBaseUrl('/');
        \Service\Registry::set('request', $request);

        $front->setRequest($request);

        return $request;
    }

    public function _initRoute()
    {
        /* @var $front \Zend_Controller_Front  */
        $front = $this->getResource('FrontController');
        /* @var $router \Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();

        $auth = $this->getResource('Auth');
        $acl = $this->getResource('Acl');

//        $routerAdvanced = new Controller\Router($router, $auth, $acl);

        $front->setRouter( Router::create($router, $auth, $acl) );
    }


    public function _initLayout()
    {
        \Zend_Layout::startMvc
        (
            array
            (
                'layoutPath' => '../application/views/layouts',
                'layout' => 'index'
            )
        );
    }


    protected function _initDb()
    {
        $_params = \Service\Config::get('resources/db/params')->toArray();

        // пока что прям так
        $db = new Mysql
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

        \Service\Registry::set('db', $db);
    }
}

