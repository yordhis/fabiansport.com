<?php

namespace App\Models;

use App\Models\{Ayudador, Validar, Arreglar};

class Formulario
{

  static function producto($producto){
    $keysValores = Ayudador::obteniendoCampoValores($producto);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun producto", 
          $keysValores['valores'][$i], "productos", "id")) return $mensaje;
        break;
        case 'codigo':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], 
          $keysValores['keys'][$i])) return $mensaje;

          if ($producto['accion'] != 'actualizar') {
            if ($mensaje =  Validar::noExisteElDato(
              "Este codigo ya existe, ingrese otro",
              $producto['codigo'],
              "productos",
              "codigo"
            )) return $mensaje;
          }
          // case 'miPadre':
              // if ($mensaje = Validar::existe($keysValores['valores'][$i], 
              // $keysValores['keys'][$i])) return $mensaje;
              // if (isset($producto['miPadre'])) {
              //   if ($mensaje =  Validar::existeColorEnFamilia(
              //     $keysValores['keys'][$i],
              //     $producto['miPadre'],
              //     $producto['idColor']
              //   )) return $mensaje;
              // }
        break;

        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], 
          $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esTextoRegular($keysValores['valores'][$i],
          $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esInt($keysValores['valores'][$i], 
          $keysValores['keys'][$i])) return $mensaje;
          break;

        case 'linea':
        case 'categoria':
        case 'marca':
        case 'sex':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], 
          $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::noEsInt($keysValores['valores'][$i], 
          $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::siExisteElDato(
            "El id de " .$keysValores['keys'][$i]." No existe en la DB",
            $keysValores['valores'][$i],
            $keysValores['keys'][$i]."s",
            "id"
          )) return $mensaje;
          break;

        case 'costo':
        case 'idColor':
          if ($mensaje =  Validar::existe($keysValores['valores'][$i],
           $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::noEsNumero($keysValores['valores'][$i],
           $keysValores['keys'][$i])) return $mensaje;
        break;
          //campos no obligatorios pueden ser null
        case 'esPadre':
        case 'descuento':
          if ($mensaje =  Validar::noEsNumero($keysValores['valores'][$i],
           $keysValores['keys'][$i])) return $mensaje;
        break;

        case 'image':
        case 'value':
          if ($mensaje =  Validar::existe($keysValores['valores'][$i],
           $keysValores['keys'][$i])) return $mensaje;
        break;

      }
    }
  }

  static function color($color){
    $keysValores = Ayudador::obteniendoCampoValores($color);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun color", 
          $keysValores['valores'][$i], "colores", "id")) return $mensaje;
          break;

        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esTextoRegularNoPermitido($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esInt($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
      }
    }
  }

  static function categoria($categoria){
    $keysValores = Ayudador::obteniendoCampoValores($categoria);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ninguna categoria", 
          $keysValores['valores'][$i], "categorias", "id")) return $mensaje;
          break;
        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esTextoRegularNoPermitido($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esInt($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
      }
    }
  }

  static function talla($talla){
    $keysValores = Ayudador::obteniendoCampoValores($talla);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ninguna talla", 
          $keysValores['valores'][$i], "tallas", "id")) return $mensaje;
          break;

          case 'idProduct':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            //Validamos que la genero que queremos editar si exista
            if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun producto", 
            $keysValores['valores'][$i], "productos", "id")) return $mensaje;
            break;

        case  'name':
          if ($mensaje =  Validar::esTextoRegularNoPermitido($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
        break;
          
        case  'code':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
        break;

        case  'quantity':
          if ($mensaje =  Validar::noEsNumero($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::noEsInt($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
      }
    }
  }

  static function genero($genero){
    $keysValores = Ayudador::obteniendoCampoValores($genero);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun genero", 
          $keysValores['valores'][$i], "generos", "id")) return $mensaje;
          break;

        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::longitudPermitida($keysValores['valores'][$i] , 100)) return $mensaje;
          if ($mensaje =  Validar::esTextoRegular($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::esInt($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
      }
    }
  }

  static function marca($marca){
    $keysValores = Ayudador::obteniendoCampoValores($marca);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ninguna marca", 
          $keysValores['valores'][$i], "marcas", "id")) return $mensaje;          
          break;
        
        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 100)) return $mensaje;
          if ($mensaje =  Validar::esTextoRegular($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case 'image':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

      }
    }
  }

  static function favorito($favorito){
    $keysValores = Ayudador::obteniendoCampoValores($favorito);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        
        case 'name':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 250)) return $mensaje;
          // if ($mensaje =  Validar::esTextoRegular($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case 'img':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case 'idUser':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun usuario", 
          $keysValores['valores'][$i], "usuarios", "id")) return $mensaje;
          break;

        case 'idProduct':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun producto", 
          $keysValores['valores'][$i], "productos", "id")) return $mensaje;
          break;

        case 'idColor':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun color", 
          $keysValores['valores'][$i], "colores", "id")) return $mensaje;
          break;

        case  'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun producto de favorito", 
          $keysValores['valores'][$i], "favoritos", "id")) return $mensaje;          
          break;
      }
    }
  }

  static function Factura($factura){
    $keysValores = Ayudador::obteniendoCampoValores($factura);
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'id':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          //Validamos que la genero que queremos editar si exista
          if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ninguna factura", 
          $keysValores['valores'][$i], "facturas", "id")) return $mensaje;
          break;

        case 'idUser':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            //Validamos que la genero que queremos editar si exista
            if($mensaje = Validar::siExisteElDato("Este id ". $keysValores['valores'][$i]  ." no esta asignado a ningun usuario", 
            $keysValores['valores'][$i], "usuarios", "id")) return $mensaje;
            break;

        case  'titular':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 150)) return $mensaje;
          if ($mensaje =  Validar::esTextoRegularNoPermitido($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case  'mount':
        case  'mountUDS':
        case  'isAdult':
          if ($mensaje =  Validar::noEsNumero($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje =  Validar::noEsInt($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case  'metodo_pago':
        case  'numero_envio':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 35)) return $mensaje;
          break;

        case  'tipo_entrega':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 35)) return $mensaje;
          break;
      }
    }
  }

  static function infoEntrega($infoFactura){
    $keysValores = Ayudador::obteniendoCampoValores($infoFactura);
    $accionIn = $infoFactura['inexistente'] ?? false;
    $accion = $infoFactura['existe'] ?? false;
    $tabla = $infoFactura['tabla'] ?? "info_factura";
    
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'code':
        case 'idUser':
        case 'name':
        case 'reference':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
        case 'departamento':
        case 'provincia':
        case 'distrito':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 100)) return $mensaje;
          break;

        case 'direccion':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 100)) return $mensaje;
          break;

        case  'email':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 250)) return $mensaje;
          break;

        case 'phone':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 50)) return $mensaje;
          break;
      }
    }
  }

  static function infoFactura($infoFactura){
    $keysValores = Ayudador::obteniendoCampoValores($infoFactura);
    $accionIn = $infoFactura['inexistente'] ?? false;
    $accion = $infoFactura['existe'] ?? false;
    $tabla = $infoFactura['tabla'] ?? "info_factura";
    
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'code':
        case 'idUser':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
        case 'businessName': //razonSocial
        case 'departamento':
        case 'provincia':
        case 'distrito':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 100)) return $mensaje;
            if ($mensaje = Validar::esTextoRegular($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;
        case 'direccion':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 100)) return $mensaje;
          break;

        case 'phone':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 50)) return $mensaje;
            break;

        case  'email':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 150)) return $mensaje;
          break;

        case  'documentID':
          if ($mensaje =  Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case  'tipoContribuyente':
        case  'typeDocument':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 30)) return $mensaje;
          break;

      }
    }
  }

  static function carrito($carrito){
    $keysValores = Ayudador::obteniendoCampoValores($carrito);
    
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        
        case 'id':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if($keysValores['vueltas'] <= 1){  
              if ($mensaje =  Validar::siExisteElDato(
                    "Este id no esta asignado a ningun producto del carrito",
                    $keysValores['valores'][$i],
                    "carritos",
                    "id"
                  )) return $mensaje;
              }
            break;
      
        case 'code':
              if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
              if($keysValores['vueltas'] <= 1){
                if ($mensaje =  Validar::siExisteElDato(
                  "Este codigo no esta asignado a ninguna factura",
                  $keysValores['valores'][$i],
                  "carritos",
                  "codigo"
                )) return $mensaje;
              }
              break;
        
        case 'quantity':
        case 'costo':
        case 'name':
        case 'image':
        case 'categoria':
        case 'marca':
        case 'linea':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            break;
        
      }
    }
  }
  
  static function usuario($usuario){
    $keysValores = Ayudador::obteniendoCampoValores($usuario);
    
    for ($i = 0; $i < $keysValores['vueltas']; $i++) {
      switch ($keysValores['keys'][$i]) {
        case 'email':
          if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          if ($mensaje = Validar::longitudPermitida($keysValores['valores'][$i], 200)) return $mensaje;
          if ( !empty($usuario['accion']) ){
            if ($usuario['accion'] != "actualizar"){
              if ($mensaje =  Validar::noExisteElDato(
                  "Este correo ya esta registrado, ingrese otro por favor",
                  $keysValores['valores'][$i],
                  "usuarios",
                  "correo"
                )) return $mensaje;
            }
          }
          break;
     
        case 'password':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
          break;

        case 'confirm':
            if ($mensaje = Validar::existe($keysValores['valores'][$i], $keysValores['keys'][$i])) return $mensaje;
            if($usuario['password'] != $usuario['confirm']) return Validar::respuestasHttp("Contraseñas no coinsiden",409);
          break;

        
      }
    }
  }

}//cierre de la clase
