<?php
//definimosuna constante para la ubicacion de los archivos

//  define('APP_PATH', __DIR__ . '../');

//definir una constante para lacarpeta PUBLIC para evitar conflicto si
//se esta en un servicor compartido
define('PUBLIC_PATH', 'https://fabiansport.com/fs');
// define('PUBLIC_PATH', 'http://localhost/fs');


//Permisos de API

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

// Constante de navegacion RUTAS
require_once 'routes.php';

//Configuracion de entorno
require_once 'env.php'; 

//Base de datos
require_once 'database.php';

//cargar todas las clases
require_once APP_PATH . 'autoload.php';

//Costantes de Square Permisos
//Manipulacion de Permisos Token
if (isset($_REQUEST['code'])) {
    require_once 'request_token.php';
}
