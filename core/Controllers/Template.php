<?php

namespace Controllers;

use HTTPS\Session as Session;
use Exceptions\jPageNotFoundException as jPageNotFoundException;
/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */

class Template {

    protected $variables = array();

     
    function __construct() {}
 
    /** Set Variables **/
 
    function setVars($vars) {
        $this->variables = $vars;
    }
    /**
    * Redirect to a specific location by given $apelle
    * @var $apelle 
    * @var $params 
    * @var $params2 
    */
    function redirect($apelle,$params = array(),$params2 = array()) {
        //get the associated url from given apelle
        $url =  \Helpers\RoutingHelper::getFromApelle($apelle,"route");

        //get expected variabled for one url
        $variables =  \Helpers\RoutingHelper::getVariableFromUrl($url);

        if((!is_array($params) && !is_null($params) ) )
            throw new \Exceptions\jMixedException("Error 1x00025 Parameter invalid \"".$params . "\" <b>".ucwords(gettype($params))."</b>" ." given when we expect an <b>Array</b>", 1);

        if((!is_array($params2) && !is_null($params2) ) )
            throw new \Exceptions\jMixedException("Error 1x00025 Parameter invalid \"".$params2 . "\" <b>".ucwords(gettype($params2))."</b>" ." given when we expect an <b>Array</b>", 1);

       if( count($params) != count($variables) )
            throw new \Exceptions\jMixedException("Trying redirect to '$apelle' failed. This redirect request are expecting some parameters, at least ".count($variables).' parameter expected.', 1);
       
       $url_chunks = explode("/", $url);
       
       $url = "";
       // queueing the params into url
       foreach ($url_chunks as $value) {
        if(strpos($value, "{") == 0 && strpos($value, "}") == strlen($value)-1){
            $url.=array_shift($params)."/";
        }else
           $url.=$value."/";
        }
        $getParams = "";
        if(count($params2)){
           $getParams = "?".http_build_query($params2);
        }
       header("Location: " . _PUBLIC_ . trim($url,"/").$getParams);
    }


   /**
   * Execute an specific controller an sets the given parameters
   *
   * @var $controller @type string The formated string giving a specific controller Module@action:Controller
   * @var $vars @type array An array whith the variables to be sets
   */
    function execute($controller,$params = array()){

    }



 
    /** 
     * Render a specific template
     * @example ModuleName@viewName:ModelPackageName 
     * @var ModuleName tells the module 
     * 
     */         
    function render($route, $vars = null){ 
        $vars != null ? $this->setVars($vars) : -1;
        $moduleName = explode("@", $route)[0];
        $modelPackageName = explode(":", explode("@", $route)[1])[1];
        $viewName = explode(":", explode("@", $route)[1])[0];
        
        $vars_to_axtract = array();
        if(count($this->variables)>0){
            foreach ($this->variables as $key => $value) {
                if(is_object($value)){                    
                    $final_array = [];
                    foreach ((array)$value as $_key => $_value) {
                        $__key = $_key;
                        $__key = str_replace("*", "", $__key);
                        /*improve this part of code, try remove keys thats star whit '_' char*/
                        if(substr($__key,0,1)!='_'){
                         $final_array[$__key] = $_value;
                        }
                    }
                    $vars_to_axtract = array_merge($vars_to_axtract,$final_array);
                }else{
                    $vars_to_axtract[$key] = $value;
                }
            }
            extract($vars_to_axtract);
            extract(array("_csft_"=>  Session::get("csft_token")));
        }

        if (file_exists(ROOT . DS . 'mods' . DS . $moduleName . DS .'Resources' . DS . "Views" . DS . $modelPackageName . DS . $viewName . '.php')) {
            //@[] para hacer
            //@() para escribir
            //$content = file_get_contents(ROOT . DS . 'mods' . DS . $moduleName . DS .'Resources' . DS . "Views" . DS . $modelPackageName . DS . $viewName . '.php');

           include (ROOT . DS . 'mods' . DS . $moduleName . DS .'Resources' . DS . "Views" . DS . $modelPackageName . DS . $viewName . '.php'); 
         //exit();
     } else {
         header("HTTP/1.0 404 Not Found");
         throw new jPageNotFoundException("Error al general URL: No existe la ruta <b>". DS .$moduleName . DS .'Resources' . DS . "Views" . DS . $modelPackageName . DS . $viewName . '.php</b>');
   }

}



 
}