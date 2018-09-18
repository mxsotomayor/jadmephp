<?php

/**
 * Description of 
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
require_once 'Services/LoaderService.php';

function jadmeLoadServiceLocator($className){
   $sl = new Services\LoaderService($className);   
   $sl->register(_CORE_."\\controllers\\");
   $sl->register(_CORE_."\\services\\");
   $sl->register(_CORE_."\\http\\");
   $sl->register(_CORE_."\\libs\\");
   $sl->register(ROOT."\\");
   $sl->load();
}

spl_autoload_register('jadmeLoadServiceLocator');


?>
