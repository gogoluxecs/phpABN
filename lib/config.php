<?php
error_reporting(E_ALL | E_STRICT);
require_once '../src/com/google/konstrukt/konstrukt.inc.php';
require_once '../src/com/anthonybush/Autoloader.php';

set_error_handler('k_exceptions_error_handler');

set_include_path(
  PATH_SEPARATOR . dirname(__FILE__).'/context/'
  . PATH_SEPARATOR . dirname(__FILE__).'/'
);

// move to external config class
foreach (explode(PATH_SEPARATOR, ini_get("include_path")) as $path)
{
  if (strlen($path) > 0 && $path{strlen($path)-1} != DIRECTORY_SEPARATOR)
    $path .= DIRECTORY_SEPARATOR;         
  
  Autoloader::addClassPath($path);
}

Autoloader::setCacheFilePath(dirname(__FILE__).'/classPathCache.txt');
Autoloader::excludeFolderNamesMatchingRegex('/^svn|git|\..*$/');

spl_autoload_register(array('Autoloader', 'loadClass'));

/*
spl_autoload_register('k_autoload');
*/
