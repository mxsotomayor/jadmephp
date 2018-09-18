<?php 

/**
 * Call all main methods of framework
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */


namespace App\Controllers;

use App\Controller;
/**
* this is default controller
*/
class DefaultController extends Controller
{
	
	
	public function index()
	{
	   $this->render("index@default", array("name" => "Maxwell"));
	}

	


}

