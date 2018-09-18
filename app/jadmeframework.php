<?php

/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */

class JadmeFramework {
    
    public static function checkRequestOrigin() {
        $cnfs = json_decode(file_get_contents(_CONFIG_ . DS . "allowed.ips"));
        if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !(in_array(@$_SERVER['REMOTE_ADDR'], $cnfs->granted) || php_sapi_name() === 'cli-server')
        ) {
            header('HTTP/1.0 403 Forbidden');
            throw new Exceptions\jForbiddenException("Sorry but you not have access granted to see this page from you location. Please contact whit <a href='#'>administrator</a>.");
        }
    }

    /**
     *  Check if environment is development and display errors
     *  * */
    public static function setReporting() {
        if (DEVELOPMENT_ENVIRONMENT == true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'runtime_error.log');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }
    }

    /** 
     * Check for Magic Quotes and remove them 
     * 
     * */
    public static function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    public static function removeMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            $_GET = stripSlashesDeep($_GET);
            $_POST = stripSlashesDeep($_POST);
            $_COOKIE = stripSlashesDeep($_COOKIE);
        }
    }

    /**
     *  Check register globals and remove them
     *  * */
    public static function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }


    /**
    *
    * Punto principal de desicion de consumo de recursos
    * decide que controller ejecutar en dependencia de la url
    * requerida, es muy imporante que en este punto el roting solo
    * se realiza en base a MVC, o sea
    * home/welcome
    * quiere decir que existe un HomeController y dentro existe un funcion welcome
    **/
    
    public static function init() {
       
       global $url;

       $_url = trim($url,"/");

       $_url = explode("/", $_url);

       $CONTROLLER_NAME = "";

       $ACTION_NAME = "";

       $PARAM = "";

       if( $_url[0] == "" ){

        $CONTROLLER_NAME = INITIAL_CONTROLLER;

        $ACTION_NAME = INITIAL_CONTROLLER_ACTION;

        $PARAM = isset($_url[2]) ? $_url[2] : array();

       }else{

       $CONTROLLER_NAME = $_url[0];

       $ACTION_NAME = isset($_url[1]) ? $_url[1] : INITIAL_CONTROLLER_ACTION;

       $PARAM = isset($_url[2]) ? $_url[2] : array();

       }
      


       $controller_fqcn = _CORE_ . "\\controllers\\" . ucwords($CONTROLLER_NAME . "Controller");

       

       if( file_exists($controller_fqcn . ".php") ){

        require_once  $controller_fqcn  . ".php";


        if(class_exists("\\app\\controllers\\" . ucwords($CONTROLLER_NAME . "Controller"))) {

           $newfqn = "\\app\\controllers\\" . ucwords($CONTROLLER_NAME . "Controller");  

           $ControllerIntance = new $newfqn();

           $rfclss = new \ReflectionClass(get_class($ControllerIntance));

           if ((int) method_exists($ControllerIntance, $ACTION_NAME)) { 


           $paramtersOfAction = $rfclss->getMethod($ACTION_NAME)->getParameters(); 

           if (count($paramtersOfAction) > count($ACTION_NAME)) {

              throw new \Exception("Esta action espera por lo menos un parametro" );

           } 

           $FUNCTION_PARAMS = array();

           if( count($paramtersOfAction) ){

            $FUNCTION_PARAMS = array($paramtersOfAction[0]->name => $PARAM);

           }

           call_user_func_array(array($ControllerIntance, $ACTION_NAME), $FUNCTION_PARAMS);

             

           }else{

              throw new \Exception("<b>" . $ACTION_NAME . "</b> no existe bajo" . ucwords($CONTROLLER_NAME . "Controller") . " " );

           }


        }else{

             throw new \Exception( ucwords($CONTROLLER_NAME . "Controller") . " es posible que existe pero no esta bajo el namespace correcto" );
        }

       }else 
          throw new \Exception( ucwords($CONTROLLER_NAME . "Controller.php") . " no existe" );
   


    }


}

