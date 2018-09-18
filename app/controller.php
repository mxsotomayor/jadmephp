<?php 

/**
 * Call all main methods of framework
 *
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com>
 */

namespace App;


/**
*  Representa la clase principal de los controllers
*/
class Controller
{
	


    /**
    *
    * render devuelve una vista
    *
    **/
	public function render($view, $params = array())
	{

		$chunks = explode("@", $view);

		if(count($chunks) != 2){
			echo "Direcion no valida, debe tener al menos dos parametros, por ejemplo vista@Carpeta, es preciso indicar extension";
			die;
		}


		if(file_exists(_CORE_ .  DS . "views" . DS . $chunks[1] . DS . $chunks[0] . ".php.html")){
			extract($params);
            require_once _CORE_ .  DS . "views" . DS . $chunks[1] . DS . $chunks[0] . ".php.html";
		}


	}


}





 ?>