<?php
/**
 * Call all main methods of framework
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */


require_once (ROOT . DS . 'app' . DS . 'jadmeframework.php');


JadmeFramework::setReporting();
JadmeFramework::removeMagicQuotes();
JadmeFramework::unregisterGlobals();


try {    
    JadmeFramework::init();
}catch(\Exception $e){
        echo $e->getMessage(); die;
}



?>
