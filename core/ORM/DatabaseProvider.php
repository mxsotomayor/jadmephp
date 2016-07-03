<?php

/**
 * Description of 
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
namespace Orm;
abstract class DatabaseProvider {
    //put your code here
    protected $_resource;        
    public    $_model;
    public    $_module;


    public abstract function connect($host_location,$username,$password,$db_name);
    public abstract function disConnect();
    public abstract function query($q);
    public abstract function error();
    public abstract function errorNo();
    public abstract function changeDb($dbname);
    
    public abstract function findAll();
    public abstract function find();
    public abstract function findBy();
    public abstract function findOneBy();
}

?>
