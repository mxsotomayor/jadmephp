<?php

namespace HTTPS;
/**
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */
class Request {

    /**
     * Query sent on Request
     * @var string 
     */
    public $query = null;

    /**
     * The method that has been used to send the request[POST|GET]
     * @var string 
     */
    public $method = null;

    /**
     * The parameters on post request
     */
    public $post = array();
    
    /**
     * The parameters on GET request
     */
    public $get = array();

    /**
     * Returns true if request is sent by post otherwise return false
     */
    public $is_post = false;

    function __construct() {
        /**
         * La vida del token es de 10 min
         */
       if(600 <= time() - Session::get("csft_token_age")){
           Session::generateNewCsft();
       }
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = $_SERVER['QUERY_STRING'];
        if ($this->method == "POST") {
            $this->is_post = true;
            $this->post = (object)$_POST;
        }else{
            $this->get = (object)$_GET;
        }  
    }

}

?>