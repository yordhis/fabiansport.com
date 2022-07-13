<?php 
namespace App\Controllers;

use App\Models\{Carrito, Validar, Ayudador, Img, Formulario};

class CarritoController
{
  private $carrito;
  //Recibimos los JSON
  public function __construct(){
    $this->carrito = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true) );
    // print_r($this->marca);
  }

  public function listar($codigoFactura){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las carrito
    if(!$carrito = Carrito::show($codigoFactura)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($carrito);
    }
  } 

  public function traerUnPoductoCarrito($idCarrito){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las carrito
    if(!$carrito = Carrito::traerUnPoductoCarrito($idCarrito)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($carrito);
    }
  } 

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

      //obtenemos los datos de la carrito y le quitamos los espacios del principio y final
      $carrito = $this->carrito['carrito'];
      
    //Cierre de validaciones
     
      //ejecutamos la creacion de la carrito
      foreach ($carrito['products'] as $producto) {
        //validamos que ningun campo este vacio
        if($mensaje = Formulario::carrito($producto)) return $mensaje;
        //Ingresamos el producto al carrito recibiendo losdatos del producto 
        //y el codigo de la factura
        $resultado = carrito::crear($producto, $carrito['codeFacture']);
      }
      if($resultado === true){
          return Validar::respuestasHttp("Los productos se añadieron al carrito de compra N°:(".$carrito['codeFacture'].") con exito", 201);

      }else {
        return Validar::respuestasHttp($resultado, 409); 
      }
  } 
  
  public function actualizar(){
    //Abrir validaciones  
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la carrito y le quitamos los espacios del principio y final
      $carrito = $this->carrito['products'];
     
      //validamos todos los campos 
      if($mensaje = Formulario::marca($carrito)) return $mensaje;

      //Datos de la DB actuales
      $carritoActual = Carrito::traerUnPoductoCarrito($carrito['idCar']);   
    //Cierre validaciones
   
    //ejecutamos la actualizacion 
    $resultado = Carrito::actualizar($carrito);
    if($resultado === true){
        return Validar::respuestasHttp("El producto (". $carritoActual['nombre'] .") del carrito ".$carritoActual['codigo']." fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("El producto (". $carritoActual['nombre'] .") del carrito ".$carritoActual['codigo']." no sufrio ningún cambio", 200); 
    }

  }
  //metodo para eliminar el carrito de compra
  public function eliminarProductoDelCarrito($idCarrito){

    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;
    
    if($mensaje = Formulario::carrito(["id" => $idCarrito])) return $mensaje;
    
    $carritoActual = carrito::traerUnPoductoCarrito($idCarrito); 

    $carrito = carrito::eliminarProductoDelCarrito($idCarrito);

    //Validamos el resultado de la acción 
    if($carrito === true){
        return Validar::respuestasHttp("Elimino un producto del carrito de la factura de Código: {$carritoActual['codigo']}",200);

    }else{
      return Validar::respuestasHttp($carrito, 409);
    }
  }

  public function eliminar($codigoFactura){

    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;
    
    if($mensaje = Formulario::carrito(["codigo" => $codigoFactura])) return $mensaje; 

    $carrito = carrito::eliminar($codigoFactura);

    //Validamos el resultado de la acción 
    if($carrito === true){
        return Validar::respuestasHttp("Elimino el carrito de la factura de Código: {$codigoFactura}",200);

    }else{
      return Validar::respuestasHttp($carrito, 409);
    }
  }



}//cierre de la clase

	
