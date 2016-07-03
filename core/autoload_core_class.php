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
   $sl->register(_CORE_."\\exceptions\\");
   $sl->register(_CORE_."\\https\\");
   $sl->register(_CORE_."\\services\\");
   $sl->register(_CORE_."\\libs\\");
   $sl->register(_CORE_."\\helpers\\");
   $sl->register(ROOT."\\");
   $sl->register(ROOT."\\mods\\");
   $sl->load();
}

spl_autoload_register('jadmeLoadServiceLocator');


?>
