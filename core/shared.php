<?php
/**
 * Call all main methods of framework
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
require_once (ROOT . DS . 'core' . DS . 'Helpers' . DS .'AssetsHelper.php');
require_once (ROOT . DS . 'core' . DS . 'jadmeframework.php');

try{
    //JadmeFramework::checkRequestOrigin();
}  catch (Exceptions\jForbiddenException $e){
    require_once _ERROR_ . DS . "500.php";
    die;
}
JadmeFramework::setReporting();
JadmeFramework::removeMagicQuotes();
JadmeFramework::unregisterGlobals();


try {    
    JadmeFramework::init();
} catch (\Exceptions\jRouterErrorException $e) {
    if(DEVELOPMENT_ENVIRONMENT){
    require_once _ERROR_ . DS . "500.php";
    }else{
       die("Server error. RouterExceptionException detected.");
    }
} catch (\Exceptions\jPageNotFoundException $e) {
    if(DEVELOPMENT_ENVIRONMENT){
    require_once _ERROR_ . DS . "500.php";
    }else{
        die("Server error. PageNotFoundException detected.");
    }    
} catch (\Exceptions\jForbiddenException $e) {
    if(DEVELOPMENT_ENVIRONMENT){
   require_once _ERROR_ . DS . "500.php";
    }else{
        die("Server error. ForbiddenException detected.");
    }
}
catch (\Exceptions\jMixedException $e) {
     if(DEVELOPMENT_ENVIRONMENT){
         require_once _ERROR_ . DS . "500.php";
    }else{
        die("Error desconocido en el sistema");
    }
}
catch (\Exceptions\jDbException $e) {
     if(DEVELOPMENT_ENVIRONMENT){
   require_once _ERROR_ . DS . "500.php";
    die;
    }else{
        die("Error desconocido en el sistema");
    }
}
catch (\Exceptions\jInvalidCsftException $e) {
     if(DEVELOPMENT_ENVIRONMENT){
     require_once _ERROR_ . DS . "500.php";
    die;
    }else{
        die("Error desconocido en el sistema");
    }
}
catch (\Exceptions\jSessionInvalidException $e) {
     if(DEVELOPMENT_ENVIRONMENT){
      require_once _ERROR_ . DS . "500.php";
    die;
    }else{
        die("Error desconocido en el sistema");
    }
   
}
catch (Exception $e) {  

     if (DEVELOPMENT_ENVIRONMENT) {
         require_once _ERROR_ . DS . "500.php";
       // die;
    } else {
       // header("Location: "._PUBLIC_);
        echo 'session destroyed';
       // session_start();
        $_SESSION['error'] = "Su sessión a caducado es posible que halla estado el máximo de tiempo permisible sin realizar alguna operación en el sistema.";
    }
  
}


?>
