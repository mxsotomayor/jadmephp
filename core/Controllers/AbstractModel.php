<?php

 /**
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
namespace Controllers;



require_once 'SQLQuery.php';

class AbstractModel extends SQLQuery {

    protected $_model;

    function __construct() {
        $this->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->_model = get_class($this);
        $fqcn = explode("\\", strtolower($this->_model));
        $this->_table = $fqcn[count($fqcn)-1];
    }

    function __destruct() {}

}