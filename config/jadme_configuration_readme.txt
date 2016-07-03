Fichero de configuración el cual define las principales constantes con las cuales el sistema realizará
las principales rutinas. Debe ser cuidadoso a la hora de modificar estas debido a que la modificación sin
el debido cuidado PODRIA causar incoherencias y esto tendría como consecuencias la inoperabilidad o el funcionamiento
no adecuado de la aplicación.

[db_host]                 :Dirección donde será alojada la base de datos,
[db_username]             :Usuario que será usado para autenticarse contra la Base de datos,
[db_password]             :Contraseña que será usada para autenticarse contra la Base de datos,
[db_name]                 :Nombre de la base de datos a manupular,
[db_providers]            :Proveedor de bases de datos implemetados,
[db_provider]             :Proveedor de conexion a utilizar para la gestion de datos,

[routing_modes]           :Modos de ruteo o acceso a recursos,
[routing_mode]            :Modo de acceso elejido,

#estos parámetros solo son válidos si la constante ROUTING_MODE = just_routing.

[init_controller]         :Controller inicial el cual se ejecutará el iniciar la app, sería el controller de inicio,
[init_action]             :Acción inicial que se ejeutará al iniciar la app, esta acción debe estar contenida dentro del INIT_CONTROLLER
[default_action]          :Acción que se ejecuta en un controller si no se especifica una como parámetro,

[max_time_alive_session]  :Tiempo máximo que dura una sessión activa, luego de esto se cerrará la sessión,

[template_extension]      :Extension bajo las cual se guardaran las paginas o templates[*.phtml,*.php,...]
