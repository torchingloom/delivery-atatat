<?php

namespace Controller;

class FrontControllerPlugin_Auth extends \Zend_Controller_Plugin_Abstract
{
	private $_auth;
	
	public function __construct (\Zend_Auth $auth, $db)
	{
		$this->_connection = $db;
		$this->_auth = $auth;
	}
	
	public function preDispatch (\Zend_Controller_Request_Abstract $request)
	{
        \Service\Auth\User::instance($this->_auth);
	}
	
	public function postDispatch(\Zend_Controller_Request_Abstract $request)
	{
		\Service\Auth\User::instance()->save();
	}
}	