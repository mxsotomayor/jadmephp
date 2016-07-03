<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author mx
 */
namespace Orm;


class Database {
    //put your code here
    private $provider;
    private $params;
    private static $_con;
    
    public function __construct($provider,$host,$userm,$pass,$dbname) {
        $this->provider = new $provider;
        $provider->connect($host,$userm,$pass,$dbname);
    }
}

?>
