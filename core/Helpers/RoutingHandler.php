<?php
namespace Helpers;

/**
 * Description of RoutingHandler
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
class RoutingHandler {
    
    
  public static function cnvApelleToRoute($apelle){
      $url = "";      
      $router_file;      
      $was_found = false;        
       if (($dh = opendir(_CONFIG_))) {
            while (($router_file_folder = readdir($dh)) !== false) {
                if (count(explode(".", $router_file_folder)) > 1 && $router_file_folder != '.' && $router_file_folder != '..' && explode(".", $router_file_folder)[1] == 'json') {
                    $router_file = _CONFIG_ . DS . $router_file_folder;
                    $acl = json_decode(file_get_contents($router_file));
                    if (isset($acl->nodes)) {
                        foreach ($acl->nodes as $value) {
                            if($value->apelle == $apelle){
                                $url = $value->route;
                                $was_found = true;
                                break;
                            }                           
                        }
                    } else {
                        header('HTTP/1.0 500 Server Error');
                        throw new \Exceptions\jRouterErrorException("Key <b>'nodes'</b> was'nt found on routing file <b>" . $this->router_file . "</b>", 1);
                    }
                }
            }
            closedir($dh);
        }
         if (($handle = opendir(ROOT . DS . "mods")) && !$was_found) {
            while (false !== ($module = readdir($handle)) && !$was_found) {
                if ($module != "." && $module != "..") {
                    if (!file_exists(ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes')) {
                         throw new \Exceptions\jRouterErrorException("Module <b>".$module."</b> seems does'nt exist. Please check your routing file.");
                         break;
                    }
                    if (($_handle = opendir(ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes')) && !$was_found) {
                        while (($router_file_folder = readdir($_handle)) && !$was_found) {
                            if (count(explode(".", $router_file_folder)) > 1 && $router_file_folder != "." && $router_file_folder != ".." && explode(".", $router_file_folder)[1] == 'json') {
                                $router_file = ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes' . DS . $router_file_folder;
                                $acl = json_decode(file_get_contents($router_file));
                                if (isset($acl->nodes)) {
                                    foreach ($acl->nodes as $value) {
                                        if ($value->apelle == $apelle) {
                                            $url = $value->route;
                                            $was_found = true;
                                            break;
                                        }
                                    }
                                } else {
                                    header('HTTP/1.0 500 Server Error');
                                    throw new \Exceptions\jRouterErrorException("Key <b>'nodes'</b> was'nt found on routing file <b>" . $router_file . "</b>", 1);
                                }
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
        
        if (!$was_found) {
           $url = $apelle;
        }        
        return $url;
    }
}

?>
