<?php

namespace Controllers;

/**
 * Description of GlobalServices
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
class GlobalServices {
    
    public static function GetAllEntities() {
        if(!file_exists(ROOT . DS . "mods")){
            throw new \Exceptions\jMixedException("No mods folder found");
            return;
        }
        if (($handle = opendir(ROOT . DS . "mods"))) {
            while (($module_name = readdir($handle))) {
                if ($module_name != "." && $module_name != ".." && $module_name != "JadDash") {
                    echo "<span style='background:#bbb;padding:3px 5px;'>$module_name\Entities</span><br>";  
                    if(file_exists(ROOT . DS . 'mods' . DS . $module_name .DS.'Entities')){
                        if(($_handle = opendir(ROOT . DS . 'mods' . DS . $module_name .DS.'Entities'))){
                            while (($className = readdir($_handle))){
                                if ($className != "." && $className != "..") {
                                echo "<a href=''>$className</a><br>";  
                                }
                            }
                            closedir($_handle);                            
                        }
                    }else{
                        echo "<a href=''>none</a><br>"; 
                    }
                }
            }
            closedir($handle);
        }
    }



    public static function GetEntitiesByMod($module_name){

        $entities = array();
        
        if(!file_exists(ROOT . DS . "mods".DS.$module_name.DS."Entities")){
            throw new \Exceptions\jMixedException("No mods folder found");
        }

        if(file_exists(ROOT . DS . 'mods' . DS . $module_name .DS.'Entities')){
            if(($_handle = opendir(ROOT . DS . 'mods' . DS . $module_name .DS.'Entities'))){
                while (($className = readdir($_handle))){
                    if ($className != "." && $className != "..") {
                        $entities[] = $className;
                    }
                }
                closedir($_handle);                            
            }
        }

        return $entities;

    }



    public static function GetAllMods(){
        $mods = array();
        if(!file_exists(ROOT . DS . "mods")){
            throw new \Exceptions\jMixedException("No mods folder found");
            return;
        }
        if (($handle = opendir(ROOT . DS . "mods"))) {
            while (($module_name = readdir($handle))) {
                if ($module_name != "." && $module_name != ".." && $module_name != "JadDash") {
                    $mods[] = $module_name;
                }
            }
            closedir($handle);
        }

    return $mods;
    }

}

?>
