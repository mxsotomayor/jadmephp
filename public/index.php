<?php    


define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('_CORE_', ROOT.DS.'core');
define('_ERROR_', _CORE_.DS.'Errors');
define('_CONFIG_', ROOT.DS.'config');

define('_ASSETS_',dirname('http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}").'/'.'assets'.'/');
define('_PUBLIC_',dirname(dirname('http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}"))."/");

 
$url = isset($_GET['jadmeurl']) ? $_GET['jadmeurl']:"";


require_once (ROOT . DS . 'core' . DS . 'autoload_core_class.php');


require_once (ROOT . DS . 'core' . DS . 'controllers' . DS . 'ConfigurationDeclarator.php');
$configDeclarator = new ConfigurationDeclarator(_CONFIG_ . DS . 'jadme_configuration.conf');
$configDeclarator->defineConstants();

require_once (ROOT . DS . 'core' . DS . 'bootstrap.php');

