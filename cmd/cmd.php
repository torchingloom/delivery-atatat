<?php


//php cmd/cmd.php [staging] test

array_shift($argv);
if (!$argv || count($argv) < 2)
{
    die('Fuck off, nigger!');
}


chdir(__DIR__ .'/../html');


if (strpos($argv[0], '[') == 0 && strrpos($argv[0], ']') == $p = (strlen($argv[0]) - 1))
{
    define('APPLICATION_ENVIRONMENT', substr(array_shift($argv), 1, $p - 1));
}

$INCOMING = $argv;

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application') .'/');
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../Library') .'/');
set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_PATH, get_include_path())));


require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENVIRONMENT, APPLICATION_PATH .'configs/application.ini');
$application->setBootstrap(APPLICATION_PATH ."/Bootstrap-CMD.php", 'BootstrapCmd');
$application->setOptions(array("ARGS" => $INCOMING));
$application->bootstrap()->run();

