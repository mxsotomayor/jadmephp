<?php

namespace Controllers;

/**
* 
*/

class ParameterBag
{
	
	function __construct(){}

	function set($key = "", $value = ""){
		$_SESSION[$key] = $value;
	}
}



?>