Fichero de configuraci�n el cual define las principales constantes con las cuales el sistema realizar�
las principales rutinas. Debe ser cuidadoso a la hora de modificar estas debido a que la modificaci�n sin
el debido cuidado PODRIA causar incoherencias y esto tendr�a como consecuencias la inoperabilidad o el funcionamiento
no adecuado de la aplicaci�n.

[db_host]                 :Direcci�n donde ser� alojada la base de datos,
[db_username]             :Usuario que ser� usado para autenticarse contra la Base de datos,
[db_password]             :Contrase�a que ser� usada para autenticarse contra la Base de datos,
[db_name]                 :Nombre de la base de datos a manupular,
[db_providers]            :Proveedor de bases de datos implemetados,
[db_provider]             :Proveedor de conexion a utilizar para la gestion de datos,

[routing_modes]           :Modos de ruteo o acceso a recursos,
[routing_mode]            :Modo de acceso elejido,

#estos par�metros solo son v�lidos si la constante ROUTING_MODE = just_routing.

[init_controller]         :Controller inicial el cual se ejecutar� el iniciar la app, ser�a el controller de inicio,
[init_action]             :Acci�n inicial que se ejeutar� al iniciar la app, esta acci�n debe estar contenida dentro del INIT_CONTROLLER
[default_action]          :Acci�n que se ejecuta en un controller si no se especifica una como par�metro,

[max_time_alive_session]  :Tiempo m�ximo que dura una sessi�n activa, luego de esto se cerrar� la sessi�n,

[template_extension]      :Extension bajo las cual se guardaran las paginas o templates[*.phtml,*.php,...]
