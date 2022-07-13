<?php 
namespace App\Controllers;

use App\Models\{Ayudador, Factura, Producto, Usuario, Validar};

class PageController
{
  private $page = [];
  //Recibimos los JSON
  public function __construct(){
    $this->page = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  
	//Paginas del sistema
  
  public function campana(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    //retornamos las facturas con estatus 0
    $facturas = Ayudador::datos(0, "facturas", "estatus", "plural");
    if($facturas):
      $notificaciones['notificaciones'] = count($facturas);
      echo json_encode($notificaciones);
      http_response_code(200);
    else: 
      echo json_encode(["notificaciones" => 0]);
      http_response_code(200);
    endif;
  }

  public function notificaciones(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    //retornamos las facturas con estatus 0
    $facturas = Ayudador::datos(0, "facturas", "estatus", "plural");
    $arregloFactura = [];
    if($facturas):
        $c = 0;
        foreach ($facturas as $factura):
        // print_r($factura);
            // Dibujamos array principal FACTURA
            $arregloFactura['facturas'][$c] = [
                "id" => intval($factura['id']),
                "codigo" => $factura['codigo'],
                "date" => $factura['fecha_pago'],
                "status" => $factura['tipo_entrega'],             
                "guia" => $factura['numero_envio'],
                "medioDePago" => $factura['metodo_pago'],
                "tiketPago" => $factura['num_comprobante']
            ];
          
            // Dibujamos el array del CARRITO
            $carrito = Ayudador::datos($factura['codigo'], "carritos", "codigo", "plural");
            $countProduct = 0;
              foreach($carrito as $producto):
                //Dibujamos el array
                $arregloFactura['facturas'][$c]['products'][$countProduct] = [
                    "id" => intval($producto['id_producto']),
                    "name" => $producto['nombre'],
                    "color" => $producto['color'],
                    "size" => $producto['talla'],
                    "img" => PUBLIC_PATH ."/img/productos/". $producto['img'],
                    "quantity" => intval($producto['cantidad']),
                    "costo" => intval($producto['costo'])  
                ];
              $countProduct++;
            endforeach;
        $c++;
        endforeach;

        echo json_encode($arregloFactura);
        http_response_code(200);

      else: 
        echo json_encode([]);
        http_response_code(200);
      endif;
  }

  public function tasa(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
    $tasa = Ayudador::fila(1, "id", "tasa", "usuarios");
    $tasaEnd = doubleval($tasa['tasa']);
    $respuesta = ["tasa"=> $tasaEnd];
    echo json_encode($respuesta);
  }

  //------------ACTUALIZAR -----------//
  //Cambiar estatus de la factura
  public function cambiarEstatus($code){
  //validamos el metodo de envio (se espera PUT)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
        
      $estatus = Factura::actualizarEstatusFactura($code);
      if($estatus):
        http_response_code(200);
      else: 
        http_response_code(400);
    endif;
  }
   // ACTUALIZAR CLIC DE PRODUCTOS
   public function clic($idProducto){
    //validamos el metodo de envio (se espera PUT)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
        
     $dataClic = Ayudador::getDato($idProducto, "productos", "clic", "id");
 
     $dataClic = $dataClic + 1;
     if(Producto::clic($dataClic, $idProducto)):
       http_response_code(200);
     else:
       http_response_code(404);
     endif;
   }

   // ACTUALIZAR CLIC DE TASA
   public function actualizarTasa()
   {
    //validamos el metodo de envio (se espera PUT)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
       
     $page = $this->page;
     if(Usuario::tasa(1, $page['tasa'])):
       http_response_code(200);
     else:
       http_response_code(400);
     endif;
   }
 


}//cierre de la clase

	
