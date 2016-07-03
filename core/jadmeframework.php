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

    
    public static function init() {
      //  var_dump();
        $dispatcher = new Controllers\Dispatcher(new Controllers\MainRouter());
        $router_mode = strtolower(ROUTING_MODE)."_dispatch";
        if(method_exists($dispatcher, $router_mode))
        return $dispatcher->$router_mode();
        else{
            throw new jMixedException("Declared router mode was'nt previously implemented. Please check the config.php file.");
        }
    }


}

