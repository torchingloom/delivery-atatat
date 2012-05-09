<?php

require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Loader/Autoloader/Interface.php';

class Autoloader implements \Zend_Loader_Autoloader_Interface
{
	/**
	 * key - lowercase class name, value path 2 class file
	 * @var array
	 */
	private static $_class_path = array
	(
	);

    public function autoload($class)
    {
        if ( ( $path = self::pathByCfg($class) ) !== null )
        {
            require_once $path;
            return;
        }

        foreach (self::pathFilePosible($class) AS $file)
        {
            if (file_exists($file))
            {
                require_once $file;
                return;
            }
        }
    }

    private static function pathFilePosible($class)
    {
        $path = preg_split('/[\\\_]/ims', $class);
        $file = str_replace('\\', '/', self::pathPrefix($class) . join('/', explode('_', $class)));

        $arr = array
        (
            $file . self::pathExtension(),
            "{$file}/". $path[count($path) - 1] . self::pathExtension(),
        );

        return $arr;
    }

    private static function pathPrefix($class)
    {
    	return LIBRARY_PATH;
    }

    private static function pathExtension()
    {
    	return '.php';
    }

    private static function pathByCfg($class)
    {
    	if ( array_key_exists( $_class = strtolower($class), self::$_class_path ) )
    	{
    		return self::$_class_path[$_class];
    	}
    	return null;
    }
}
 

class Autoload_Exception extends \Exception
{
	
}
