<?php

namespace HTTPS;
/**
 * Description of Session
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
class Session {
    
    function __construct() {
        
//        session_start();
//        if(!isset($_SESSION['created_time'])){            
//            $_SESSION['created_time'] = time();
//        }else{
//            if(MAX_TIME_ALIVE_SESSION < time() - $_SESSION['created_time']){
//                session_unset();
//                session_destroy();
//                header("Location: ./");
//            }
//        }        
    }

    /**
     * Set defaults params for a session
     * @param    string $sess_name Specific name for a session
     * @param     array  $data      Array of parameters for a session
     */
    
    public static function register($sess_name = "userapp",array $data = array()) {
        if(isset($_SESSION[$sess_name]))
            unset($_SESSION[$sess_name]);        
        $_SESSION[$sess_name] = (object)$data;
        
        if(!isset($_SESSION['csft_token']))            
         $_SESSION['csft_token'] = md5(uniqid(rand(), true)).str_shuffle("abcdefgyijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
        
        if(!isset($_SESSION['csft_token_age']))
         $_SESSION['csft_token_age'] = time();
        
    }
    
    public static function get($session_name = "userapp"){
        if(isset($_SESSION[$session_name]))
           return $_SESSION[$session_name];
        else
            throw new jSessionInvalidException("Session required is not valid");
    }
    
    public static function generateNewCsft(){
        if(!isset($_SESSION['csft_token']))            
         $_SESSION['csft_token'] = md5(uniqid(rand(), true)).str_shuffle("abcdefgyijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
        
        if(!isset($_SESSION['csft_token_age']))
         $_SESSION['csft_token_age'] = time();
    }

    /**
     * Erase all session on the app
     */
    public static function eraseAll(){
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Erase one session on the app
     */
    public static function erase($session_name){
        if(isset($_SESSION[$session_name])){
            unset($_SESSION[$session_name]);
        }
    }
    
    /**
     * Checks if the given session name exists
     */
    public static function exists($session_name = "userapp"){
        return isset($_SESSION[$session_name]);
    }
    
    /**
     * Checks if the given role is authenticated on the app
     */
    public static function isGranted($role_name = "ANONIMOUS"){
        return isset($_SESSION["userapp"]->role) && $_SESSION["userapp"]->role == $role_name;
            
    }


}

?>
