<?php
namespace Controllers;



require_once 'Template.php';
require_once 'ParameterBag.php';



/**
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 *
 */

class AbstractController extends Template {



    function __construct() {  }

     
    function getOrm() {
        //$db_provider = ucwords(DB_PROVIDER)."DbProvider";
        return new \Orm\Orm(new \Orm\MysqlDbProvider());
    }

    function getParameterBag(){
         return new ParameterBag();
    }
    

    function getCurrentUser(){
       return \HTTPS\Session::get('userapp');
    }
    
    
}