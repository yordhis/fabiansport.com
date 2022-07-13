<?php

namespace App\Controllers;

use App\Models\{Factura, Ayudador, Arreglar, Formulario};
use App\Models\{Carrito, Validar, Img, Correo, Talla, Producto};

class FacturaController
{
  private $factura;
  //Recibimos los JSON
  public function __construct()
  {
    $this->factura = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
    // print_r($this->factura);
  }

  //admin facturas
  public function indexAdmin(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    $p = $_GET['page'] ?? 1;
    $facturas = [];
    //consulta de la lista de facturas
    $facturas = Factura::listar("*",$p);
    $facturas['totalPage'] = intval($facturas['totalPage']);
    $facturas['pageActual'] = intval($facturas['pageActual']);
    //validamos
    if (empty($facturas['facturas'])){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      $facturas['facturas'] = Arreglar::facturas($facturas['facturas']);
      http_response_code(200);
      echo json_encode($facturas);
    }
   

  }

  //usuario facturas
  public function misCompras($id){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    //consulta de la lista de facturas
    $facturas = Factura::usuario($id);

    //validamos
    if (empty($facturas)){
      return Validar::respuestasHttp("No hay compras", 200);
    }else{
      $jsonFacturas = Arreglar::facturas($facturas);
      http_response_code(200);
      echo json_encode($jsonFacturas);
    }
  }

  public function detalleCompra($code){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    //consultamos los detalles de la compra 
    $factura = Factura::show($code);
    //validamos
    if(empty($factura)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else {
      $detalleCompra = Arreglar::detalleCompra($factura);
      echo json_encode($detalleCompra);
      http_response_code(200);
    }
  }

  // REGISTRO DE FACTURA
    public function crear(){
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], 
      "POST")) return $mensaje;
    
      //recibimos los datos
      $factura = $this->factura['pay'];
      $productos = $this->factura['products'];
      // Validar cantidades
      foreach ($productos as $product) {
        $tallaStock = Talla::show($product['size']['id']);
        if($product['quantity'] > $tallaStock['cantidad']){
          return Validar::respuestasHttp("Stock insuficiente de producto {$product['name']} de la talla {$tallaStock['nombre']}", null);
        }
      }
      
      //Generamos el codigo de la factura
      $factura['code'] = Ayudador::codigo();

      //Validamos tipo de usuario
      if($factura['idUser'] != 1){
        //Validamos que halla imagen
        if(!empty($_FILES['image'])){
          //Normalizar imagen del bauche para el registro y la movemos al directorio
            $img["name"] = [$_FILES['image']['name']];
            $img["tmp_name"] = [$_FILES['image']['tmp_name']];
            $imagenLista = Img::mover($img, 'comprobantes', $factura['code']);

             //Asignamos la imagen del comprobante a la factura 
            $factura['image'] = $imagenLista; 

            // VALIDACION DE TODOS LOS DATOS RECIBIDOS 
            // en caso de no pasar una validacion eliminamos la imagen y damos el mensaje
            if($mensaje = Formulario::factura($factura)){ 
              Img::eliminar($factura['image'], 'comprobantes/');
              return $mensaje;
            }
        }else {
            return Validar::respuestasHttp('Ingrese la imagen del comprobante', null);
        }
      }else {
        //Asignamos la imagen del comprobante a la factura 
        $factura['image'] = "Admin.png"; 
      }

      //Registramos los datos de la factura
      $resulFactura = Factura::crear($factura);

      //validamos el resultado
      if ($resulFactura != true){
        if($factura['idUser'] != 1){
          Img::eliminar($factura['image'], 'comprobantes/');
          return Validar::respuestasHttp($resulFactura, 409);
        }else {
          return Validar::respuestasHttp($resulFactura, 409);
        }
      }else{
      //ejecutamos la creacion del carrito
        foreach ($productos as $producto){ 
          //Ingresamos el producto al carrito 
          $resultado = Carrito::crear($producto, $factura['code']);
          //verificamos el resultado
          if($resultado != true){
            return Validar::respuestasHttp($resultado, 409);
          }
        }

        //ejecutamos la actualizacion de cantidades y stock
        foreach ($productos as $producto){
          //consultamos los datos de las tallas
          $talla = Talla::show($producto['size']['id']);
          //Restamos la cantidad de la factura con la de tallas
          $producto['size']['quantity'] = $talla['cantidad'] - $producto['quantity'];
          //actualizamos la cantidad existente de la talla
          Talla::actualizarCantidad($producto['size']);
          //actualizamos el stock
          $resultado = Producto::actualizarStock($producto['id']);
          //verificamos el resultado
          if($resultado != true){
            return Validar::respuestasHttp($resultado, 409);
          }

          //verificamos si el producto padre queda con stock cero para  actualizarlo y hacerlo hijo
          $cambioDeRol = Producto::stockCero($producto['id']);
          // if ($cambioDeRol === true) {
          //   var_dump($cambioDeRol);
          //   return;
          // }else {
          //   var_dump($cambioDeRol);
          //   return;
          // }

        }
     
          //obtenemos el id de la venta
          $idVenta = Ayudador::getDato($factura['code'], "facturas", "id", "codigo");
          //validamos
          if(empty($idVenta)){
            return Validar::respuestasHttp("no hay id asignado a esta venta", 409);
          }else {
            
            //Enviar correo
            if(!Correo::compra($factura)) echo Validar::respuestasHttp("fallo el envio de correo", 409);

            //Finalizamos y damos una respuesta
              http_response_code(201);
              return json_encode(["code" => $factura['code'], "idVenta" => $idVenta]);
            
          }
      }  
    }
  


  //ACTUALIZAR ESTATUS DE LA FACTURA
  public function actualizarEstatusFactura(){
    //validamos el metodo de envio (se espera PUT)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
    //recibimos los datos
    $factura = $this->factura;

    $resultado = Factura::actualizarEstatusFactura($factura);
    if($resultado){
      return Validar::respuestasHttp("Ok", 200);
    }else{ 
      return Validar::respuestasHttp($resultado, 409);
    }
  }

  public function asignarInfoEntrega()
  {
    //validamos el metodo de envio (se espera PUT)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
    
    $resultado = Factura::asignarInfoEntrega($this->factura);
    if($resultado){
     return Validar::respuestasHttp("Ok.", 200);
    }else{
     return Validar::respuestasHttp($resultado, 409);
    }
  }

}//Cierre de la clases
