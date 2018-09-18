<?php
 
/**
 * Description of LoaderService
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
namespace Services;


class LoaderService {

    protected $poolLocations;
    protected $className;

    function __construct($className) {
        $this->className = $className;
        $this->poolLocations = [];
        $this->poolLocations[] = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
    }

    function register($location) {
        $this->poolLocations[] = $location;
    }

    function loadFromMods() {
        #iterate on modules here
    }

    function canLocate($file) {
        $loaded = false;
       
        
        foreach ($this->poolLocations as $location) {
            $loaded = $this->canBeAutoLoaded($location . $file . ".php");
            
            if ($loaded) {
                break;
            }
        }
        return $loaded;
    }

    function canBeAutoLoaded($file) {
        if (file_exists($file)) {            
            require_once strtolower($file);
            return true;
        }
        else
            return false;
    }

    function load() {
        $chunks = explode("\\", $this->className);
        //whereter class comes formatted simply <<clasName>>
        if (count($chunks) == 1) {
            if (count($this->poolLocations) == 0) {
                throw new \Exception("Not locations registered", 1);
            }
            if (!$this->canLocate($this->className)) {
                $parts = explode("\\", $this->className);
                die("I can't load '".$this->className."'");

            }
            // whether class name comes formated as <<names\space\className>>
        } else {
            $file_name = implode(DIRECTORY_SEPARATOR, $chunks);
            if (!$this->canLocate($file_name)) {
                $parts = explode("\\", $file_name);
                $_clasName_ = array_pop($parts);
                throw new \Exception("You are trying instanciate <i>'". $_clasName_ . "'</i> class under <i>'".implode("\\", $parts)."'</i> namespace. It doesn't exists.", 1);
            }
        }
    }

}

?>
