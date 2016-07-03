<?php
namespace Helpers;

/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */
class Assets {

    /**
     * 
     * 
     */
    static function js($resource) {
        echo sprintf("<script type='text/javascript' src='%s%s'></script>", _ASSETS_, $resource);
    }

    /**
     * 
     * 
     */
    static function css($resource) {
        echo sprintf("<link rel='stylesheet' href='%s%s'/>", _ASSETS_, $resource);
    }

    /**
     * 
     * 
     */
    static function img($resource, $attrs = array()) {
        $_attrs = "";
        foreach ($attrs as $key => $value) {
            $_attrs.=$key . '="' . $value . '"';
        }
        echo sprintf("<img src='%s%s' %s/>", _ASSETS_, $resource, $_attrs);
    }

    /**
     * Create a link html tag
     * @param string $apelle It's the name that use in the route to identify a specific route
     * @param string $text It's the text inside the link
     * @param array $attrs Array whith attr that could have the link     * 
     */
    static function link($apelle = '', $text = 'mylink', $attrs = array()) {
        $url = "";
        $url =  RoutingHandler::cnvApelleToRoute($apelle);
        
        
        
        $_attrs = "";
        foreach ($attrs as $key => $value) {
            $_attrs.=$key . '="' . $value . '"';
        }
        $a = sprintf("<a href='%s%s' %s> %s", _PUBLIC_, $url, $_attrs, $text) . "</a>";
        echo $a;
    }

    /**
     * Acceso a las rutas definidas en las rutas estas solo apuntan a funciones
     * @param string $appelle Este es el pseudonombre con el que se pueden llamr las rutas en 
     * el sistema
     * @param int $typeit Tipo de operaci'on que desea, en caso que sea 1 escribe la ruta en caso contrario solo la devuelve
     */
    static function pathRoute($apelle = '', $typeit = 1) {
       
            $url = "";
            
            $acl = json_decode(file_get_contents(_CONFIG_.DS."acl.json"));
            foreach ($acl->nodes as $value) {
                if ($apelle == $value->apelle)
                    $url = $value->route;
            }
            
            if ($handle = opendir(ROOT . DS . "mods")) {
        while (false !== ($module = readdir($handle))) {
            if ($module != "." && $module != "..") {
                if ($_handle = opendir(ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes')) {
                    while (false !== ($route = readdir($_handle))) {
                        if (count(explode(".", $route)) > 1 && $route != "." && $route != ".." && explode(".", $route)[1] == 'json') {
                            $router_file_full_path = ROOT . DS . 'mods' . DS . $module . DS . 'Resources' . DS . 'Routes' . DS . $route;
                            $acl = json_decode(file_get_contents($router_file_full_path));
                            if (isset($acl->nodes)) {
                                foreach ($acl->nodes as $node) {
                                    if($node->apelle == $apelle){
                                        $url = $node->route;
                                        break;
                                    }
                                }
                            } else {
                                header('HTTP/1.0 500 Server Error');
                                throw new jRouterErrorException("No se encontró la clave <b>'nodes'</b> en el archivo <b>" . $router_file_full_path . "</b>", 1);
                            }
                        }
                    }
                }
            }
        }
        closedir($handle);
    }
            if ($typeit == 1)
                echo _PUBLIC_ . $url;
            else
                return _PUBLIC_ . $url;
        
    }

    /**
     * Acceso a las rutas definidas en las rutas estas solo apuntan a funciones
     */
    static function pathResource($url) {
        echo _ASSETS_ . $url;
    }
    
    static function extend($tmpl){
    if(file_exists(ROOT.DS."mods".DS.explode("@", $tmpl)[1].DS."Resources".DS."Views".DS."__Partials".DS.explode("@", $tmpl)[0].".php"))
        require_once ROOT.DS."mods".DS.explode("@", $tmpl)[1].DS."Resources".DS."Views".DS."__Partials".DS.explode("@", $tmpl)[0].".php";
    else{
        throw new \Exceptions\jMixedException("Se intenta extender de una plantilla que no existe <b>".ROOT.DS."mods".DS.explode("@", $tmpl)[1].DS."Resources".DS."Views".DS."__Partials".DS.explode("@", $tmpl)[0].".php</b>");
    }
    
    
}

public static function signIt(){
//    echo sprintf("<input type='' name='%s' value='%s' >",'token',  Session::get()->csft_token);
        echo 'firmar';
}

}