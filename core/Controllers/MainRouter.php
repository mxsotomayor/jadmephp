<?php

/**
 * Description of MainRouter
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */

namespace Controllers;

use Controllers\IRouter as IRouter;
use HTTPS\Session as Session;
use Exceptions\jRouterErrorException as jRouterErrorException;

class MainRouter implements IRouter {

    /**
     * Given all url it parse it a return
     * @param string $url A url formated as example/exmple/asda
     */
    public $appelles;
    protected $router_file;
    protected $function_parameters;
    protected $defaults_pattern = "/^([a-zA-Z]{1,})+([0-9]*([a-zA-Z]{1,})*)*@([a-zA-Z]{1,})+([0-9]*([a-zA-Z]{1,})*):([a-zA-Z]{1,})+([0-9]*([a-zA-Z]{1,})*)$/";

    public function __construct() {
        $this->appelles = [];
    }

    public function defaultsHasError($defaults) {
        return !preg_match($this->defaults_pattern, $defaults);
    }

    /**
     * This method allows detect if a given route hs error
     *
     *
     */
    public function routeHasError($current_route) {
        $response = false;

        foreach (explode("/", $current_route) as $path_route_chunk) {
            if (
                    (is_integer(strpos($path_route_chunk, "{")) && !strpos($path_route_chunk, "}")) ||
                    (is_integer(strpos($path_route_chunk, "}")) && !strpos($path_route_chunk, "{"))
            ) {
                $response = true;
                break;
            } elseif (
                    (is_integer(strpos($path_route_chunk, "{")) && strpos($path_route_chunk, "{") !== 0) &&
                    (is_integer(strpos($path_route_chunk, "}")) && strpos($path_route_chunk, "}") !== strlen($path_route_chunk) - 1)
            ) {

                $response = true;
                break;
            }
        }
        return false;
    }
    /**
     * 
     */
    function parseRouteFile($url) {
        $objectResponse = false;

        //alias duplicated
        $error_alias_duplicated = false;

        //se cambia a true si el HTTP_REQUEST_TYPE con que esta declarado esta no es mismo con el que se llama
        $not_allowed_method = false;
        
        //si se declara un metodo mal en la ruta
        $wrong_method = false;
        
        $method = "";

        $apelle_wrong = "";

        $appeles = [];

        //error parsing defaults
        $error_parsing_defaults_node = false;

        $file_unparseable = false;

        //para cuando no encuetre algun nodo
        $error_parsing_node = false;

        $error_nodes_key_not_found = false;

        //error parsing rote declared
        $error_parsing_route_node = false;

        $acl = json_decode(file_get_contents($this->router_file));
        //checks if json has right format
        if ($acl === null) {
            $file_unparseable = true;
        }
        
        if ($file_unparseable)
            throw new \Exceptions\jRouterErrorException("Was imposible import file " . $this->router_file . ". It hasn't right json format.");

        //checks if node key exist
        if (isset($acl->nodes)) {
            foreach ($acl->nodes as $current_routing_node) {
                //defaults node doesn't WAS found
                if (!isset($current_routing_node->apelle) ||
                        !isset($current_routing_node->route) ||
                        !isset($current_routing_node->role) ||
                        !isset($current_routing_node->defaults)) {
                    $error_parsing_node = true;
                    $apelle_wrong = $current_routing_node;
                    break;
                } elseif (!is_string($current_routing_node->role)) {
                    $error_parsing_node = true;
                    $apelle_wrong = $current_routing_node;
                    break;
                }
                //check for duplicated apelles 
                if (!in_array($current_routing_node->apelle, $appeles)) {
                    $appeles[] = $current_routing_node->apelle;

                    //checks if current node route has error
                    $route_chunk_check_response = $this->routeHasError($current_routing_node->route);
                    if ($route_chunk_check_response) {
                        $error_parsing_route_node = true;
                        $apelle_wrong = $current_routing_node->apelle;
                        break;
                    }
                    //checks if the global url and the current node route match
                    if ($this->matchPath($url, $current_routing_node)) {                        
                        //if this node is protected for a specific http method
                        if (isset($current_routing_node->method)) {
                            //get the declarated method
                            $method = $current_routing_node->method;
                            if (!in_array(strtoupper($method), array("GET", "POST", "PUT", "DELETE"))) {
                                $wrong_method = true;
                                break;
                            }
                            //checks if node method is callable for current HTTP REQUEST METHOD
                            // declarated method == $_SERVER['REQUEST_METHOD']
                            if (strtolower($method) !== strtolower($_SERVER['REQUEST_METHOD'])) {
                                $not_allowed_method = true;
                                break;
                            }
                        }
                        
                        //response object
                        $objectResponse = (object) array("error" => false, "responseText" => "", "mod" => explode("@", $current_routing_node->defaults)[0], "action" => explode(":", explode("@", $current_routing_node->defaults)[1])[0], "controller" => explode(":", explode("@", $current_routing_node->defaults)[1])[1], "method" => isset($current_routing_node->method) ? $current_routing_node->method : "GET", "parameters" => $this->function_parameters);
                    }
                } else {
                    $error_alias_duplicated = true;
                    break;
                }
                //checks if the format of defaults keys is right
                $error_parsing_defaults_node = $this->defaultsHasError($current_routing_node->defaults);
                if ($error_parsing_defaults_node) {
                    $apelle_wrong = $current_routing_node;
                    break;
                }
            }
        } else {
            $error_nodes_key_not_found = true;
            break;
        }

        if ($error_nodes_key_not_found)
            throw new \Exceptions\jRouterErrorException("We can't import the router file <i>'" . $this->router_file . "'</i>. Key <i>'nodes'</i> was'nt found.", 1);
        elseif ($error_alias_duplicated)
            throw new \Exceptions\jRouterErrorException("Alias <i>'" . $current_routing_node->apelle . "'</i> repetido en el fichero <i>'" . $this->router_file . "'</i> esto puede causar fallos en el ruteo de los recursos de la aplicaci&oacute;n.");
        elseif ($error_parsing_node || $error_parsing_route_node || $error_parsing_defaults_node)
            throw new \Exceptions\jRouterErrorException("<b>Error x0111</b>. We can't import the routing file <i>'" . $this->router_file . "'</i>. We've found some error on node <br><i><h4>'" . json_encode($apelle_wrong) . "'</h4></i>");
        elseif ($wrong_method)
            throw new \Exceptions\jPageNotFoundException("<b>Error x0113</b>. Resource requested " . $_SERVER['REQUEST_METHOD'] . " <i>'$url'</i> couldn't be found. It route expects <b>" . strtoupper($method) . "</b> HttpRequestMethod and seems it doesn't a valid choice, you've pick up one of this: " . implode(", ", array("(PUT", "GET", "POST", "REMOVE", "UPDATE).")));
        elseif ($not_allowed_method)
            throw new \Exceptions\jPageNotFoundException("<b>Error x0113</b>. Resource requested " . $_SERVER['REQUEST_METHOD'] . " <i>'$url'</i> couldn't be found. You trying using <b>" . $_SERVER['REQUEST_METHOD'] . "</b> and it expects <b>" . strtoupper($method) . "</b>.");
        

        return $objectResponse;
    }

    /**
     * Este metodo tiene la responsabilidad de convertir a una ruta en un un recurso
     * o sea saber apartir de una ruta dada cual es el controller que se debe ejecutar
     * @param string $url Esta es la ruta que viene por el navegador o sea <esta/esmi/ruta_ok>
     */
    public function parseRoute($url) {
        /* objeto vacio para poblar y devolver como respuesta */
        $response = false;

        /* busco todas las rutas en la carpeta config */

        if (($dh = opendir(_CONFIG_))) {
            while (($router_file_folder = readdir($dh)) !== false) {
                if (count(explode(".", $router_file_folder)) > 1 && $router_file_folder != '.' && $router_file_folder != '..' && explode(".", $router_file_folder)[1] == 'json') {
                    $this->router_file = _CONFIG_ . DS . $router_file_folder;
                    $response = $this->parseRouteFile($url);
                }
            }
            closedir($dh);
        }

        /* buscando las rutas en todos los modulos */
        if (($handle = opendir(ROOT . DS . "mods")) && !$response) {
            while (false !== ($module = readdir($handle)) && !$response) {
                if ($module != "." && $module != "..") {
                    if (!file_exists(ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes')) {
                        throw new jRouterErrorException("Module <b>" . $module . "</b> seems does'nt exist. Please check your routing file.");
                        break;
                    }
                    if (($_handle = opendir(ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes')) && !$response) {
                        while (($router_file_folder = readdir($_handle)) && !$response) {
                            if (count(explode(".", $router_file_folder)) > 1 && $router_file_folder != "." && $router_file_folder != ".." && explode(".", $router_file_folder)[1] == 'json') {
                                $this->router_file = ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes' . DS . $router_file_folder;
                                $response = $this->parseRouteFile($url);
                            }
                        }//end of folder iterator
                    }
                }
            }
            closedir($handle);
        }

        if (!$response) {
            throw new \Exceptions\jPageNotFoundException("<b>Error x0114</b>. Resource requested " . $_SERVER['REQUEST_METHOD'] . " <i>'$url'</i> wasn't found. Probably it route doesn't exist. Check the requested url please.");
        }
        
        return $response;
    }

    /**
     * This function tries match a route whit a url
     * @param string $url The current url
     * @param NodeObject{apelle,route,default,..} $node thi's a node object of the routings file
     */
    public function matchPath($url, $node) {      
        
        if ($this->samePattern($url, $node->route)) {
            // test if the current user role is granted to access to the given route
            // checks if the route requested can be accesible for the current role active            
            $role_trees = json_decode(file_get_contents("../config/role_tree.roles"));
            
            $current_role = Session::get('userapp')->role;
            
            $route_role = $node->role;
           
            if(Session::get('userapp')->role === $route_role = $node->role)
                return true;
            
            if (isset($role_trees->$current_role) && isset($role_trees->$route_role)) {
                $role_is_defined = false;
                
                //buscando si el rol actual esta definido para esta ruta
                //en el routing file
                foreach ($role_trees->$current_role as $defined_role) {
                    if ($defined_role == $node->role) {
                        $role_is_defined = true;
                        break;
                    }
                }
                if (!$role_is_defined) {
                    return true;
                } else {                    
                    throw new \Exceptions\jForbiddenException("No esta permitido accede a esta ruta : <i>" . _PUBLIC_."</i><b>".$node->route . "</b> para el rol <b>" . $current_role."</b>");
                    return false;
                }
            } else {
                throw new \Exceptions\jForbiddenException("Role <b>" . $node->role . "</b> is not difined on tree roles, this is the possible value for this route " . implode(", ", $role_trees->$current_role));
                return false;
            }
            die;
        } else {
            return false;
        }
    }

    /**
     * This function tries to figure out if a url has a same pattern whith a route
     * @param string $url esta es la direccion url
     */
    public function samePattern($url, $route) {


        if ($url == $route) {
            $this->function_parameters = array();
            return true;
        } else {
            $url_chunks = explode("/", $url);
            $route_chunks = explode("/", $route);
            if (count($route_chunks) != count($url_chunks)) {
                return false;
            }
            //variables
            $route_difference = array_diff($route_chunks, $url_chunks);
            //valores de las variables
            $url_difference = array_diff($url_chunks, $route_chunks);
            if (count($url_difference) > 0 && count($url_difference) == count($url_chunks))
                return false;
            $param_keys = array();
            foreach ($route_difference as $route_chunk) {
                $init_pattern = strpos($route_chunk, "{");
                $close_pattern = strpos($route_chunk, "}");
                $total_size = strlen($route_chunk) - 1;

                if ($init_pattern == 0 && $init_pattern == false && $close_pattern == $total_size) {
                    $param_keys[] = trim($route_chunk, "{}");
                } else {
                    $param_keys = array();
                    break;
                }
            }
            if (!$param_keys) {
                $this->function_parameters = array();
                return false;
            }
            $this->function_parameters = array();
            foreach ($param_keys as $current_routing_node) {
                $this->function_parameters[$current_routing_node] = array_shift($url_difference);
            }
            return true;
        }
    }

}

?>
