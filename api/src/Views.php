<?php
  
    namespace App;
   

    // Esta clase es la que me va a dibujar en pantalla la peticion excigida
    class Views {
        // Este metodo recibe dos parametros uno es la direccion del archivo y el 
        // otro es las variables o datos requeridos por la vista 
        public static function render($fileView, array $variables = [])
        {
            /**
             * $array = ['cosa1' => 'Esto es una cosa', 'cosa2' => 'Esto es otra cosa' ];
             * extract($array);
             * Lo que hace esta funcion es convertir un array en variables
             * Ejemplo:
             * $cosa1
             * $cosa2
             */

             extract($variables);
            //  require_once APP_PATH . "/views/$fileView.view.php";
        }
    }
