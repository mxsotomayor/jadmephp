<?php

/**
 * Description of Dispatcher
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */

namespace Controllers;

use HTTPS\Session as Session;
use HTTPS\Request as Request;
use Exceptions\jMixedException as jMixedException;

class Dispatcher {

    protected $main_router;
    protected $session;

    function __construct(IRouter $router = null) {
        $this->main_router = $router;
    }

    /**
     * 
     * Initiate session
     */
    private function try_init_session() {
        session_start();
        if (!Session::exists("userapp"))
            Session::register("userapp", array("username" => "public", "password" => "public", "role" => "ANONIMOUS_ROLE"));
        else {
//            if (!isset($_SESSION['created_time'])) {
//                $_SESSION['created_time'] = time();
//            } else {
//                if (MAX_TIME_ALIVE_SESSION < time() - $_SESSION['created_time']) {
//                    session_unset();
//                    session_destroy();                    
//                    throw new Exception("Session expired");
//                }
//            }
        }
    }

    /**
     * Dispatch a Controller
     */
    function dispatch($controller, $action, $dispatcher_parameters) {
        $rfclss = new \ReflectionClass(get_class($controller));
        if ((int) method_exists($controller, $action)) {            
            /* parameters thats the controller action expects */
            $paramtersOfAction = $rfclss->getMethod($action)->getParameters();
            if (count($paramtersOfAction) > count($dispatcher_parameters)) {
                $msj = "Some parameters are not invalid";
                throw new jMixedException("Action <b>$action</b> of <b>" . get_class($controller) . "</b> expects " . count($paramtersOfAction) . " parameters and you're sending just " . count($dispatcher_parameters) . ". A <b>Request</b> object is included.", 0, null, "no file detected", $msj);
            } else {               
               call_user_func_array(array($controller, $action), $dispatcher_parameters);
            }
        } else {
            throw new jMixedException(
            "Function <strong>" . $action . "</strong> does'nt found under controller class <strong>" . get_class($controller) . "</strong>", 1, null, "no file detected", "Check this controller class for existence of this action."
            );
        }
    }

    /**
     * 
     * Dispatching using only roting files
     */
    function just_routing_dispatch() {
        global $url;

        $url = trim($url,"/");

        $this->try_init_session();
        $resp = $this->ripUrl();
        
        if ($resp->error) {
            header('HTTP/1.0 500 Server Error');
            throw new jMixedException($resp->responseText, 1);
            return;
        };

        if(!in_array(ucwords($resp->mod), \Controllers\GlobalServices::GetAllMods())){
            throw new \Exceptions\jRouterErrorException("The module <i>'".$resp->mod."'</i> does'nt exist, please your check your routing files.");            
        }

        if(!file_exists(ROOT . DS . "mods" . DS . $resp->mod . DS . "Controllers" . DS . ucwords($resp->controller . "Controller.php"))){
            throw new \Exceptions\jRouterErrorException("<b>Error x0112</b>. Sorry but we can't find <i>'". ucwords($resp->controller . "Controller.php")."'</i> for you, seems that it doesn't exist.");            
        }

        $controller_fqcn = $resp->mod . "\\Controllers\\" . ucwords($resp->controller . "Controller");
        
        require_once  ROOT . DS. "mods". DS . $controller_fqcn.".php";
        
        if(class_exists($controller_fqcn))        
           $dispatch = new $controller_fqcn();
       else
          throw new \Exceptions\jRouterErrorException("<b>Error x0112</b>. We can't instanciate <i>'".$resp->controller."Controller'</i> class under <i>'".$resp->mod . "\\Controllers"."'</i> namespace. We found the file <i>'".$controller_fqcn.".php'</i> but seems that namespaces or class name doesn't match.");            
       
        $function_dispatcher_parameters = array_merge(array(new Request()), $resp->parameters);
        
        if ((int) method_exists($controller_fqcn, $resp->action . "Action")) {
            if ($url == "" && isset(Session::get("userapp")->name)) {
                    $defaultDspatcher = INIT_MODULE . "\\Controllers\\" . INIT_CONTROLLER . "Controller";
                    $this->dispatch(new $defaultDspatcher(), "welcomeAction", $function_dispatcher_parameters);
            } else {
                $this->dispatch($dispatch, $resp->action . "Action", $function_dispatcher_parameters);
            }
        } else {
            header('HTTP/1.0 404 Not Found');
            throw new jMixedException("<b>Error x0112</b>. La funci&oacute;n <jadtag><i>'" . $resp->action . "Action'</i></jadtag> no pudo ser llamada correctamente en <i>'".$resp->controller."Controller</i>'. Probablemente  no est&aacute; definida. <span class='badge badge-info'>sdfsd</span>");
        }
    }

    /**
     * 
     * Dispatch using only mvc
     */
    function just_mvc_dispatch() {
        global $url;
        $this->try_init_session();
        $dispatcher_parameters = array(new Request());
        $action = "defaultAction";
        $params = array();

        if ($url != "") {
            $urlChunks = explode("/", $url);
            $controller = ucwords(array_shift($urlChunks)) . "Controller";
            $_action = array_shift($urlChunks);
            if ($_action != "") {
                $action = strtolower($_action) . "Action";
            }
            $params = $urlChunks;
        } else {
            $controller = ucwords(INIT_CONTROLLER) . "Controller";
            $action = INIT_ACTION . "Action";
        }
        $this->dispatch(new $controller(), $action, array_merge($dispatcher_parameters, $params));
    }

    function ripUrl() {
        global $url;
      //  $url = trim($url,"/");
        return $this->main_router->parseRoute($url);
    }

    //put your code here
}

?>
