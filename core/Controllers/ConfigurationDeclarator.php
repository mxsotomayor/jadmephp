<?php 

class ConfigurationDeclarator{
   protected $file;

   function __construct($configuration_file){
     $this->file = $configuration_file;
   }

   function defineConstants(){
      if(!file_exists($this->file))
         throw new Exceptions\jMixedException("Configuration file seems does'nt exist.", 1);

      $conf_obj = json_decode(file_get_contents($this->file));

      if($conf_obj === null)
         throw new Exceptions\jMixedException("Error trying parse $this->file file. Please check it.", 1);

      foreach ($conf_obj as $node_key => $node_value) {
         if($node_key != 'routing_modes' && $node_key != 'db_providers'){
            define(strtoupper($node_key), $node_value);
         }
      }
   }
}
 ?>