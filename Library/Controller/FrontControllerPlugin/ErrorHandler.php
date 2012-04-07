<?php
namespace Controller;

class FrontControllerPlugin_ErrorHandler extends \Zend_Controller_Plugin_ErrorHandler
{
	public function __construct(Array $options = array())
	{
		parent::__construct($options);
	}	
}