<?php

/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */

/**
 * 
 * 
 */
function js($resource) {
    echo sprintf("<script type='text/javascript' src='%s%s'></script>", _ASSETS_, $resource);
}

function bagExist($key){
    return isset($_SESSION[$key])?true:false;
}

function printBag($key = ""){
    if(bagExist($key)){
        echo $_SESSION[$key];
        unset($_SESSION[$key]);
    }
}

/**
* traduce
*
*/
function transText($keyword,$lang){
    if(file_exists(ROOT . DS . "public" . DS . "lang". DS . $lang . DS . $keyword.".txt"))
       echo file_get_contents(ROOT . DS . "public" . DS . "lang". DS . $lang . DS . $keyword.".txt");
   else
    throw new \Exceptions\jTranslateError("Sorry but we can't translate the keyword <i>'".$keyword."'</i> to <b>".$lang."</b>, probably the language wan't declared jet or keywork doesn't exists.", 1);
    
}

/**
* traduce imagenes
*
*/
function transImage($key,$lang){
    //poder hacerlo por bd o por ficheros
}

/**
 * 
 * 
 */
function css($resource) {
    echo sprintf("<link rel='stylesheet'  href='%s%s'/>", _ASSETS_, $resource);
}

/**
 * Write the image
 * 
 */

function  imgW($resource, $attrs = array()){

    echo  img($resource, $attrs);
}

/**
 * Return the address of the given image
 * 
 */
function img($resource, $attrs = array()) {
    $_attrs = "";
    foreach ($attrs as $key => $value) {
        $_attrs.=$key . '="' . $value . '"';
    }
    return sprintf("<img src='%s%s' %s/>", _ASSETS_, $resource, $_attrs);
}

/**
 * Create a link html tag
 * @param string $apelle It's the name that use in the route to identify a specific route
 * @param string $text It's the text inside the link
 * @param array $attrs Array whith attr that could have the link     * 
 */

function  aLinkW($apelle = '', $text = 'mylink', $attrs = array()){
    echo aLink($apelle = '', $text = 'mylink', $attrs = array());
}

function aLink($apelle = '', $text = 'mylink', $attrs = array()) {
    $url;

    if ($apelle != "#" && !preg_match("/javascript/i", $apelle)){
        $url = Helpers\RoutingHelper::getFromApelle($apelle, "route");
        $url = _PUBLIC_.$url;
    }else
    $url = $apelle;
    $_attrs = "";
    foreach ($attrs as $key => $value) {
        $_attrs.=$key . '="' . $value . '"';
    }
    $a = sprintf("<a href='%s' %s> %s", $url, $_attrs, $text) . "</a>";
    return $a;
}

/**
 * Acceso a las rutas definidas en las rutas estas solo apuntan a funciones
 * @param string $appelle Este es el pseudonombre con el que se pueden llamr las rutas en 
 * el sistema
 * @param int $typeit Tipo de operaci'on que desea, en caso que sea 1 escribe la ruta en caso contrario solo la devuelve
 */
function path($apelle = "", $params = array(), $typeit = 1) {

    $url = Helpers\RoutingHelper::getFromApelle($apelle, "route");
    $variables = Helpers\RoutingHelper::getVariableFromUrl($url);

    if (count($params) != count($variables))
        throw new \Exceptions\jRouterErrorException("<b>Error x0003</b>. This route $apelle expects " . count($variables) . " parameters and you give us " . count($params) . ". Expected values: " . implode(",", $variables));

    $url_chunks = explode("/", $url);
    if (count($params) > 0) {
        $url = "";
        foreach ($url_chunks as $url_chunk) {
            if (is_integer(strpos($url_chunk, "{")) && 0 == strpos($url_chunk, "{")) {
                $data = trim($url_chunk, "{}");
                $url.=array_shift($params) . "/";
            } else {
                $url.=$url_chunk . "/";
            }
        }
    }

    if ($typeit == 1)
        echo _PUBLIC_ . rtrim($url, "/");
    else
        return _PUBLIC_ . rtrim($url, "/");
}

/**
 * Acceso a las rutas definidas en las rutas estas solo apuntan a recursos
 * est'aticos como imaagenes, js, css,...
 */
function asset($url) {
    echo _ASSETS_ . $url;
}

/**
 * This method allows render an specific Controller from the View template with the apelle
 * @param string $apelle Esta es el apelle definido
 * @param array $params Un arreglo de paramatros segun los que espera la ruta
 */
function render($apelle = "", $params = array()) {
    if ($apelle == "")
        throw new \Exceptions\jRouterErrorException("<b>Error x0002</b>. The given apelle is a null value, please give us something to match with the apelle's routing files.");

    $default = Helpers\RoutingHelper::getFromApelle($apelle, "defaults");
    $url = Helpers\RoutingHelper::getFromApelle($apelle, "route");
    $variables = Helpers\RoutingHelper::getVariableFromUrl($url);

    if (count($params) != count($variables))
        throw new \Exceptions\jRouterErrorException("<b>Error x0002</b>. This route expects " . count($variables) . " parameters and you give us " . count($params) . ". Expected values: " . implode(",", $variables));

    $action = explode(":", explode("@", $default)[1])[0];

    $controlleFqcn = "\\" . explode("@", $default)[0] . "\\" . "Controllers" . "\\" . explode(":", explode("@", $default)[1])[1] . "Controller";
    $param_arr = array_merge(array(new \HTTPS\Request()), $params);

    $controller = new $controlleFqcn();
    $globalDispatcher = new \Controllers\Dispatcher();
   return $globalDispatcher->dispatch($controller,$action. "Action",$param_arr);
}

function extend($tmpl) {
    if (file_exists(ROOT . DS . "mods" . DS . explode("@", $tmpl)[1] . DS . "Resources" . DS . "Views" . DS . "__Partials" . DS . explode("@", $tmpl)[0] . ".php"))
        require_once ROOT . DS . "mods" . DS . explode("@", $tmpl)[1] . DS . "Resources" . DS . "Views" . DS . "__Partials" . DS . explode("@", $tmpl)[0] . ".php";
    else {
        throw new \Exceptions\jMixedException("<b>Error x0001</b>: We can't find template : <i>" . ROOT . DS . "mods" . DS . explode("@", $tmpl)[1] . DS . "Resources" . DS . "Views" . DS . "__Partials" . DS . explode("@", $tmpl)[0] . ".php'</i>");
    }
}

function signIt() {
//    echo sprintf("<input type='' name='%s' value='%s' >",'token',  Session::get()->csft_token);
    echo 'firmar';
}