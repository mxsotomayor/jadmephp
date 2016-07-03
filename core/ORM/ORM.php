<?php

/**
 * Description of 
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
namespace Orm;

class ORM {
    public $db_provider;
    
    function __construct(DatabaseProvider $provider) {
        $this->db_provider = $provider;
    }
    
    function getRepoClass($className){
        $this->db_provider->_model = explode(":", $className)[0];
        return $this->db_provider;
    }
}

?>
