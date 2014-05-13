 <?php

      define('DS', DIRECTORY_SEPARATOR);
      define('WS', '/');

      define('ROOT', realpath(dirname(__FILE__)) . DS);

      define('APP', ROOT . 'application' . DS);
      define ('SERV', 'http://'.$_SERVER['SERVER_NAME']);
      //no volem accedir directament a les variables globals

      $phpServer= filter_input(INPUT_SERVER, 'SCRIPT_NAME',FILTER_SANITIZE_URL);  

      define('APP_W',dirname($phpServer));  

      define ('SYS',ROOT .'system'.DS);
      define ('TPL',ROOT .'application'.DS.'public'.DS.'tpl');
      define ('APPL',ROOT .'application');

      define('DEFAULT_CONTROLLER',"Index");

      define('DEFAULT_ACTION',"index");
      define('ESTILS',APP_W.WS.'application'.WS.'public'.WS.'css'.WS);
      define('JS',APP_W.WS.'application'.WS.'public'.WS.'js'.WS);

         function __autoload($class_name)

      {
         //Les classes comencen per majúscula
             
             $class=APP."controllers".DS.ucfirst(strtolower($class_name)).'.php';
             
             if (file_exists($class)){
                 require_once ($class);
             }
             $model=APP."models".DS.ucfirst(strtolower($class_name)).'.php';
             
             if (file_exists($model)){
                 require_once ($model);
             }
             

         $file=SYS.ucfirst(strtolower($class_name)).'.php';
         if (file_exists($file)){
              require_once($file);
      }
  }