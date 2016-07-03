<?php 

/**
* 
*/
class GenerateDb
{
	protected $tables;
	protected $class;
	protected $classLocations;
	protected $difference;

	function __construct()
	{
		# code...
	}

	function connect(){
     if (!mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
			throw new Exception("Error Processing Request");	
		}
	}

	function mainMenu()
	{
		echo "Realizando operacion. Espere por favor ...".PHP_EOL;
		$nombre_bd = DB_NAME;
		
		try {
			$this->connect();
		} catch (Exception $e) {
			Init();
		}

		$sql = "SHOW TABLES FROM $nombre_bd";
		$resultado = mysql_query($sql);

		if (!$resultado) {
			echo "Error de BD, no se pudieron listar las tablas\n";
			echo 'Error MySQL: ' . mysql_error();
			exit;
		}
		/*all tables on database*/
		$this->tables = array();
		while ($fila = mysql_fetch_row($resultado)) {
			$this->tables[] = $fila[0];
		}

		$this->class = array();
		$this->classLocations = array();
		if ($handle = opendir(ROOT.DS."mods")) {
			while (false !== ($moduleFolder = readdir($handle))) { 
				if($moduleFolder != "." && $moduleFolder != ".."){       
					if($_handle_ = opendir(ROOT . DS . 'mods' . DS . $moduleFolder .DS.'Entities')){
						while (false !== ($classmoduleFolder = readdir($_handle_))) {
							if($classmoduleFolder != "." && $classmoduleFolder != ".."){
								$className = explode(".", strtolower($classmoduleFolder))[0];
								$this->class[] = $className;
								$this->classLocations[$className] = ROOT . DS . 'mods' . DS . $moduleFolder .DS.'Entities'.DS.$className.".php";
							}

						}
					}
				}
			}
			closedir($handle); 
		}

		$this->difference = array_diff($this->class, $this->tables);

		foreach ($this->classLocations as $key => $value) {
			if(!in_array( $key, $this->difference))
				unset($this->classLocations[$key]);
		}
		if(count($this->difference)>0){
			echo "==============================================================".PHP_EOL;
			echo "Las siguientes tablas NO se encuentran aun en la Base de Datos".PHP_EOL;
			echo "==============================================================".PHP_EOL;
			foreach ($this->difference as $value) {
				echo "- ".$value.PHP_EOL;
			}
			//	var_dump($this->classLocations);

			echo "==============================================================".PHP_EOL;
			echo "Desea sincronizar su Base de Datos apartir del Modelo. (Y/N)";
		}else{
			echo "==============================================================".PHP_EOL;
			echo "Base de datos y Modelos sincronizados.Congrats!".PHP_EOL;
			echo "==============================================================".PHP_EOL;
		}

		mysql_free_result($resultado);

		/*prueba*/
        $this->sincronize();

		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		if(strtolower(trim($line)) == "n")
			Init();
		else{
			/*original*/
			$this->sincronize();
		}
	}

	public function sincronize()
	{

		$className = 'city';
		$sd = new $className();
		$sd->createTable();
		sleep(20);

	}
}



?>