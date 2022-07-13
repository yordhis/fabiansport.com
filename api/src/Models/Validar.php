<?php

namespace App\Models;

use App\Models\{Producto, Ayudador};

class Validar
{

    //si el dato existe retornamos un mensaje de error $mensaje
    //no existe el dato retornamos false
    static function noExisteElDato($mensaje, $datoBuscar, $tabla, $campoComparar){
      if (Ayudador::datos($datoBuscar, $tabla, $campoComparar, "singular")){
        return  Validar::respuestasHttp($mensaje,409);
      }else return false;
    }

    //si existe el dato no hacemos nada solo retornamos false
    //no existe el dato retornamos un mensaje
    static function siExisteElDato($mensaje, $datoBuscar, $tabla, $campoComparar){
      if (!Ayudador::datos($datoBuscar, 
      $tabla, $campoComparar, "singular")){
        return  Validar::respuestasHttp($mensaje, 409);
      }else {
        return false;
      }
    }

    static function existeColorEnFamilia($campo, $miPadre, $idColor){
      if (Ayudador::datosRelacion($miPadre, 
      $idColor, "mi_padre", "id_color",
      'productos', '*', '')){
        return  Validar::respuestasHttp("Este color ya lo posee otro producto de tu familia de modelos, por favor seleccione otro", 409);
      }else {
        return false;
      }
    }

    static function existe($valor, $campo){
        if(empty($valor)){
         return  Validar::respuestasHttp("El campo $campo no puede estar vacio", 409);
        }else return false;
    }
    //para correo
    static function esTextoRegularCorreo($valor, $campo){
      $patronTexto = "/^[a-zA-Z0-9@.\s]+$/";
        if(!preg_match($patronTexto, $valor)){
         return  Validar::respuestasHttp("El campo $campo tiene caracteres no permitidos", 409);
        }else return false;
    }
    static function esTextoRegularNoPermitido($valor, $campo){
      $patronTexto = "/^[0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ$@%&\s]+$/";
        if(preg_match($patronTexto, $valor)){
         return  Validar::respuestasHttp("El campo $campo tiene caracteres no permitidos", 409);
        }else return false;
    }
    //solo numero
    static function esTextoRegularNoPermitidoEnCodigo($valor, $campo){
      $patronTexto = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
    
        if(preg_match($patronTexto, $valor) == 0){
         return  Validar::respuestasHttp("El campo $campo tiene caracteres no permitidos", 409);
        }else return false;
    }
    //solo texto, acentos y numeros
    static function esTextoRegular($valor, $campo){
      $patronTexto = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
        if(!preg_match($patronTexto, $valor)){
         return  Validar::respuestasHttp("El campo $campo tiene caracteres no permitidos", 409);
        } else return false;
    }

    static function esNumero($valor, $campo){
        if(is_numeric($valor)){
         return  Validar::respuestasHttp("El campo $campo no acepta datos numéricos", 409);
        }else return false;
    }

    static function noEsNumero($valor, $campo){
        if(!is_numeric($valor)){
         return  Validar::respuestasHttp("El campo $campo solo acepta datos numéricos",409);
        }else return false;
    }

    static function esInt($valor, $campo){
      if(is_int($valor)){
       return  Validar::respuestasHttp("El campo $campo no acepta tipo datos enteros", 409);
      }else return false;
    }

    //detectamos si el valor no es entero y si no es entero le mostramos el mensaje
    static function noEsInt($valor, $campo){
      //para detectar recibimos el numero separado por un . y si tenemos dos elementos 
      //determinamos que es decimal 
      $numeros = explode('.',$valor);
      if(count($numeros) > 1){
       return  Validar::respuestasHttp("El campo $campo solo acepta tipo datos enteros",409);
      }else return false;
    }

    static function esNumeroEstricto($valor, $campo){
      if(is_float($valor) || is_int($valor)){
       return  Validar::respuestasHttp("El campo $campo solo acepta tipo datos enteros y décimales",409);
      }else return false;
    }
    // Validamos los metodos de envio del formulario GET , POST, PUT, DELETE
    static function solicitudCorrecta($metodoRecibido, $metodoEsperado){
      if($metodoRecibido != $metodoEsperado){
        $mensaje = "el metodo de envio no es aceptable, por favor verifique el metodo de envio. Método recibido: ";
        return Validar::respuestasHttp(  $mensaje . $_SERVER['REQUEST_METHOD'] . ", método esperado: " . $metodoEsperado, 409);
      }else return false;
    }
    
    //limitar la longitud de datos que se desea insertar en la DB
    static function longitudPermitida(string $cadenaDeTexto, int $longitudPermitida){
      $longitudEntrante = strlen($cadenaDeTexto);
      if($longitudEntrante > $longitudPermitida){
        return Validar::respuestasHttp("La cadena de texto supera la cantidad de caracteres permitida, limite {$longitudPermitida}", 409);
      }else return false;
    }
    
    //------------------ Mensajes de Error --------------------//
    static function mensajes($mensajeError){
      $mensaje = '{"mensaje":"' . $mensajeError .'."}';
      return $mensaje;
    } 

    static function mensajeSql($informacionError){
      if(!empty($informacionError)){
         $codigo = substr($informacionError[0],0,5);
          $mensaje = ERRORES_BASEDATOS[$codigo]." -> código ($codigo)".$informacionError[2];
          return  $mensaje;
      }else return false;
    } 

    //Mensajes de Error
    static function respuestasHttp($mensaje = null, $codigoHttp){
      http_response_code($codigoHttp);
      $mensaje = '{"mensaje":"' . $mensaje .'."}';
      return $mensaje;
    } 
    
}


