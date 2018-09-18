<?php    




/**
 * Call all main methods of framework
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */


// esta constante es muy importante es para acceder a los
// 
define('DS', DIRECTORY_SEPARATOR);

// defineido el root the la aplicacion
define('ROOT', dirname(dirname(__FILE__)));

// 
define('_CORE_', ROOT.DS.'app');
 
// obtener la url solicitada
$url = isset($_GET['jadmeurl']) ? $_GET['jadmeurl']:"";

// el autoloader, muy importante pues permite cargar clases
// de forma dinamica, es vital
require_once (ROOT . DS . 'app' . DS . 'core' . DS . 'autoload_core_class.php');

// aqui vi la configuraion general sin importar
// el tipo de ambiente
require_once (ROOT . DS . 'app' . DS . 'configs' . DS . 'configuration.php');

// decidiendo que archivo de configuration
// incluri en dependencia del modo de desarrollo
if(DEVELOPMENT_ENVIRONMENT){
	require_once (ROOT . DS . 'app' . DS . 'configs' . DS . 'prod_configuration.php');
}else 
    require_once (ROOT . DS . 'app' . DS . 'configs' . DS . 'dev_configuration.php');

 // archivo para realiar el boostrating de la aplicacion
 // es el amarra los cordones de tus zapatos
require_once (ROOT . DS . 'app' . DS . 'bootstrap.php');

