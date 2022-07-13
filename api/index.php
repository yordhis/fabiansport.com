<?php

define('APP_PATH', __DIR__ . '/');

require_once './config/app.php';

use App\Route;

// // Recibimos los datos de la url
$url_completa = $_GET['url'] ?? '';
$url=explode("/", $url_completa);
// // Usamos la constante de ROUTES (rutas) para mi navegacion
$route = ROUTES[$url[0]] ?? false;

if ($route) 
{
  $controller = $route['controllers'];
  
  $action = $route['action'];
  
  if (isset($url[1])) {
    echo Route::any($controller, $action, $url[1]);
  } else {
    echo Route::any($controller, $action);
  }
} 
else 
{
    $respuesta = ["mensaje"=>"pagina no existe"];
    http_response_code(404);
    echo json_encode($respuesta);
}

?>
