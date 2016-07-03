<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MysqlDbProvider
 *
 * @author mx
 */

namespace Orm;

require_once 'DatabaseProvider.php';


class MysqlDbProvider extends DataBaseProvider {
    
    function __construct() {
        $this->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }


    public function connect($host_location, $username, $password, $dbname) {
        $this->_resource = new \mysqli($host_location, $username, $password, $dbname);
        if ($this->_resource->connect_errno) {
            die("Error conectando db");
            error_log($this->_resource->connect_error);
        }
        else
        return $this->_resource;
    }

    public function disConnect() {
        return $this->_resource->close();
    }

    public function error() {
         return $this->_resource->error;
    }

    public function errorNo() {
         return $this->_resource->errno;
    }

    public function query($q) {
        $result = array();
        
        $model_name = ucwords($this->_model);
        $fqcn = "\\Core\\Entities\\".$model_name;

        if(preg_match("/SELECT/i", $q)){
            $q_result = $this->_resource->query($q);
                
            
            for($it  = 0; $it < $q_result->num_rows; $it++ ){
               $row = mysqli_fetch_field($q_result);
               var_dump($row);
            }
            die;
            
            
            
            $entity = new $fqcn();
            $result[] = $entity;
            return $result;           
        }
        else
        return $this->_resource->query($q);
    }

    public function changeDb($dbname) {
        $this->_resource->select_db = $dbname;
    }  
    
    public function findAll() {
        $sql = "SELECT * FROM $this->_model";
        return $this->query($sql);
    }

    public function find() {
        
    }

    public function findBy() {
        
    }

    public function findOneBy() {
        
    }
}

?>
