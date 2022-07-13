<?php 
//Este archivo o clase se encarga de manejar las rutas
namespace App;

use App\controllers\PageController;

class Route {

    public static function any($controller = null, $action = "index" , $param = null)
    {
    // Si el controlador existe me instancias el controlador
        if($controller) {
            $controller = "\\App\\controllers\\{$controller}Controller";
            $controller = new $controller;
        } else {
        // instancia controller por defecto
            $controller = new PageController;
        }
        // Creamos un arreglo asociativo y otro logico para los parametros
        // enviados por la url
        if (method_exists($controller, $action)) 
        {
            return $controller->$action($param);
        }
      /*
        if($param != null)
        { 
            $c = count($param);
            // si solo hay dos parmetro que seria el controlador y el id hacer lo siguiente:
            if ($c == 2)
            {
                if (method_exists($controller, $action)) {
                    return $controller->$action($param);
                }
            }
            // asociativo 
            for ($i=1; $i < $c ; $i++) 
            { 
                $paramEnd[$param[$i]] = isset($param[$i+1]);
                $i++;
            }
            // Logico
            $paramEnd[0] = $param;

            // Ejecutamos la respuesta del servidor
            if (method_exists($controller, $action)) {
                return $controller->$action($paramEnd);
            }
        }
        else
        {
            if (method_exists($controller, $action)) {
                return $controller->$action($param);
            }
        }
      */
        
    }

}

//var_dump($variables = Route::any("categoria/1/marca/2/descuento/1/relevancia/2"));
