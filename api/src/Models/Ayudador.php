<?php

namespace App\Models;

use App\Models\{Producto, Img, Talla};
use Exception;

class Ayudador
{
  //metodo para destruir un registro de producto
  static function destruirRegistro($idProducto, $img){
    $idProducto = intval($idProducto);
    if($img = Img::eliminar($img, 'productos/') == true);
    else echo Validar::respuestasHttp($img, 200);
    if($talla = Talla::eliminar($idProducto));
    else return Validar::respuestasHttp($talla, 409);
    if($producto = Producto::eliminar($idProducto)) return true;
    else return Validar::respuestasHttp($producto, 409);
  
  }


  //quitar espacios del principio y final
  static function quitarEspacios($datos){
      $datosSanos = [];
      foreach ($datos as $key => $value) {
          $datosSanos[$key] =  trim($value);
      }
      return $datosSanos;
  }
  
  //validamos los envios y asignamos los datos enviados a la variable 
  static function getDatosRecibidos($metodo = null, $formulario = null, $json = null){
    if (!empty($metodo) && $metodo == 'POST'){
      if(!empty($formulario)){
        return $formulario;
      }elseif (!empty($json)) {
        return $json;
      }else{
        return null;
      }
    } elseif(!empty($metodo) && $metodo == 'PUT'){
        if(!empty($formulario)){
          return $formulario;
        }elseif (!empty($json)) {
          return $json;
        }else{
          return null;
        }
      }elseif(!empty($metodo) && $metodo == 'DELETE'){
        if(!empty($formulario)){
          return $formulario;
        }elseif (!empty($json)) {
          return $json;
        }else{
          return null;
        } 
      }
  }

  
//GENERAR TOKEN
  static function generarToken($correo){
    $token = md5(time() . md5($correo));
    return $token;
  }

  //GENERAR CODIGO FACTURA
  static function codigo(){
    include(APP_PATH . 'config/database.php');
    $codigo;
    $sql = "SELECT MAX(codigo) FROM facturas ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute();
    $codigo = $sentencia->fetch();

    if ($codigo["MAX(codigo)"] > 0){
      $codigo = intval($codigo["MAX(codigo)"]) + 1;
    }else{
      $codigo =  1310000;
    }

    return $codigo;
  }

  //COIGOS DE RESPUESTAS 
  static function http_response_code($code = NULL){
    if (!function_exists('http_response_code')) {
      function http_response_code($code = NULL)
      {

        if ($code !== NULL){

          switch ($code) {
            case 100:
              $text = 'Continue';
              break;
            case 101:
              $text = 'Switching Protocols';
              break;
            case 200:
              $text = 'OK';
              break;
            case 201:
              $text = 'Created';
              break;
            case 202:
              $text = 'Accepted';
              break;
            case 203:
              $text = 'Non-Authoritative Information';
              break;
            case 204:
              $text = 'No Content';
              break;
            case 205:
              $text = 'Reset Content';
              break;
            case 206:
              $text = 'Partial Content';
              break;
            case 300:
              $text = 'Multiple Choices';
              break;
            case 301:
              $text = 'Moved Permanently';
              break;
            case 302:
              $text = 'Moved Temporarily';
              break;
            case 303:
              $text = 'See Other';
              break;
            case 304:
              $text = 'Not Modified';
              break;
            case 305:
              $text = 'Use Proxy';
              break;
            case 400:
              $text = 'Bad Request';
              break;
            case 401:
              $text = 'Unauthorized'; //no autorizado
              break;
            case 402:
              $text = 'Payment Required';
              break;
            case 403:
              $text = 'Forbidden';
              break;
            case 404:
              $text = 'Not Found';
              break;
            case 405:
              $text = 'Method Not Allowed';
              break;
            case 406:
              $text = 'Not Acceptable';
              break;
            case 407:
              $text = 'Proxy Authentication Required';
              break;
            case 408:
              $text = 'Request Time-out';
              break;
            case 409:
              $text = 'Conflict';
              break;
            case 410:
              $text = 'Gone';
              break;
            case 411:
              $text = 'Length Required';
              break;
            case 412:
              $text = 'Precondition Failed';
              break;
            case 413:
              $text = 'Request Entity Too Large';
              break;
            case 414:
              $text = 'Request-URI Too Large';
              break;
            case 415:
              $text = 'Unsupported Media Type';
              break;
            case 500:
              $text = 'Internal Server Error';
              break;
            case 501:
              $text = 'Not Implemented';
              break;
            case 502:
              $text = 'Bad Gateway';
              break;
            case 503:
              $text = 'Service Unavailable';
              break;
            case 504:
              $text = 'Gateway Time-out';
              break;
            case 505:
              $text = 'HTTP Version not supported';
              break;
            default:
              exit('Unknown http status code "' . htmlentities($code) . '"');
              break;
          }

          $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

          header($protocol . ' ' . $code . ' ' . $text);

          $GLOBALS['http_response_code'] = $code;
        } else {

          $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }

        return $code;
      }
    }
  }

  // Devuelve datos especificados en la variable campo de una tabla en especifico
  static function getNombre($id, $tabla, $campos){
    include(APP_PATH . 'config/database.php');
    $sql = "SELECT $campos FROM $tabla WHERE id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);

    if ($sentencia->execute()) {
      $resultado = $sentencia->fetch();
      return $resultado[0];
    } else {
      return false;
    }
  }

  // Retorna un dato de una fila en la tabla espesificada 
  static function getDato($id, $tabla, $retornarCampos, $compararCon)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT $retornarCampos FROM $tabla WHERE $compararCon = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);

    if ($sentencia->execute()) {
      $resultado = $sentencia->fetch();
      
      return $resultado[0];
    } else {
      return false;
    }

  }

  //Retornar todo de una tabla o los campos que yo espesifique
  //se ordena por el ID de manera predeterminada
  static function getDatos($tabla, $campo, $ordenarPor = "id ASC"){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campo FROM $tabla ORDER BY $ordenarPor";
    $sentencia = $pdo->prepare($sql);
    if($sentencia->execute()){
      $resultados = $sentencia->fetchAll(); return $resultados;
    }
    else return false;
  }
  //documentacion del metodo datos()
    /**
     *  @var DATOS metodo de consulta
     *  Me devuelve todos los datos de una tabla o fila
     *  
     *  @var dato es un identificador @var id o @var codigo
     *  @var tabla es la tabla que se va a consultar en la dataBase
     *  @var campo esta variable es el campo que quiero comparar de la dataBase
     *  @var return Esta me permite modificar el retorno de datos usando  
     *  --------------->@var singular o @var plural 
     *  
     */

  static function datos($dato, $tabla, $compararConDato, $tipoDeRetorno = "sigular", $campoDefila = "*", $ordenar = "id DESC"){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campoDefila FROM $tabla WHERE $compararConDato = :campo ORDER BY $ordenar ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':campo', $dato);

    if ($sentencia->execute()){
      if ($tipoDeRetorno == "plural") $tipoDeRetorno = "fetchAll";
      else $tipoDeRetorno = "fetch";
      $resultados = $sentencia->$tipoDeRetorno();
      return $resultados;
    }else{
      return false;
    }
  }

  static function query($condiciones, $tabla, $tipoDeRetorno = "sigular", $campoDefila = "*", $ordenar = "id DESC"){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campoDefila FROM $tabla WHERE $condiciones  ORDER BY $ordenar ";
    
    $sentencia = $pdo->prepare($sql);

    if ($sentencia->execute()){
      if ($tipoDeRetorno == "plural") $tipoDeRetorno = "fetchAll";
      else $tipoDeRetorno = "fetch";
      $resultados = $sentencia->$tipoDeRetorno();
      return $resultados;
    }else{
      return false;
    }
  }

  // Me devuelve todos los datos de una tabla filtrando lo por dos condiciones
  static function datosRelacion($datoCondicion1, $datoCondicion2, $nombreColumna1, 
  $nombreColumna2, $tabla, $campoDefila = "*", $tipoDeRetorno = "sigular", $ordenDeRespuesta = " ORDER BY id ASC"){

    include(APP_PATH . 'config/database.php');

    $sql = "SELECT $campoDefila FROM $tabla WHERE $nombreColumna1 = :datoCondicion1 AND $nombreColumna2 = :datoCondicion2 $ordenDeRespuesta";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':datoCondicion1', $datoCondicion1);
    $sentencia->bindParam(':datoCondicion2', $datoCondicion2);

    if ($sentencia->execute()) {
      //Validamos el tipo de retorno solicitado
      if ($tipoDeRetorno == "plural") $tipoDeRetorno = "fetchAll";
      else $tipoDeRetorno = "fetch";

      $resultado = $sentencia->$tipoDeRetorno();
      return $resultado;
    } else return false;
  }

  // Esta funcion me devuelve un campo, segun el id 
  static function fila($id, $nombreColumna, $campoDefila, $tabla){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campoDefila FROM $tabla WHERE $nombreColumna = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();
    if ($resultado = $sentencia->fetch()) {
      return $resultado;
    } else {
      return false;
    }
  }

  // Devuel dos array con los valores y keys de un arreglo asociativo
  static function obteniendoCampoValores($formulario){
    $vueltas = count($formulario);
      for ($i=0; $i < $vueltas; $i++) { 
        $arrayKeys[] = key($formulario);
        $arrayCombine[] = $i;
        next($formulario);
      }
     
    $arrayValores = array_combine($arrayCombine, $formulario);
    
    $respuesta['keys'] = $arrayKeys;
    $respuesta['valores'] = $arrayValores;
    $respuesta['vueltas'] = $vueltas;

    return $respuesta;
  }
}//cierre de clase
