<?php

define('APPLICATION_ENVIRONMENT', $_SERVER['APPLICATION_ENVIRONMENT'] ?: $_SERVER['APPLICATION_ENVIROMENT']);
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application') .'/');
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../Library') .'/');
set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_PATH, get_include_path())));

require_once 'Zend/Application.php';

$application = new Zend_Application(APPLICATION_ENVIRONMENT, APPLICATION_PATH .'configs/application.ini');
$application->bootstrap()->run();
