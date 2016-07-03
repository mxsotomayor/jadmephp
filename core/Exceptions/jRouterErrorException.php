<?php

/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */
namespace Exceptions;

class jRouterErrorException extends jException {
   
    
    function __construct($message, $code = null, $previous = null) {
        header("HTTP/1.1 400");
        parent::__construct($message, $code, $previous);
    }
}

?>