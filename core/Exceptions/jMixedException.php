<?php
/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */
namespace Exceptions;

use Exceptions\jException as jException;

class jMixedException extends jException{
    public $current_file;
    public $custom_messages;
            function __construct($message, $code=null, $previous=null,$file=null,$custom_message=null) {
                header("HTTP/1.1 500");
        parent::__construct($message, $code, $previous);
        $this->current_file = $file;
        $this->custom_messages = $custom_message;
    }

}

?>
