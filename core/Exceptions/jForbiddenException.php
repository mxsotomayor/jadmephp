<?php
/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */
namespace Exceptions;


class jForbiddenException extends jException {
    //put your code here
    
    public function __construct($message = "You are not granted to access this resource.", $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

?>
