<?php 
	namespace App\Controllers;

	
	use App\Views;
	use App\Models\Consultar;
  use App\Models\DataPage;
  use App\Models\Metodo;

	class ConsultarController
	{
    protected $json;
    //detectar filttro automaticamente
    public function __construct()
    {
      if ($_SERVER["REQUEST_METHOD"] == "POST") 
      {
        if(isset($_POST['json'])):
          if($this->json = json_decode($_POST['json'])):else:
            echo '{"mensaje": "El JSON no se pudo decodiicar"}';
          endif;
        else: 
          $this->json = null;
        endif;
      } 
     
    }
    //admin
    public function facturas()
    {
      $p = $_GET['page'] ?? 1;
      $arreglo_factura;
     //consulta de la lista de facturas
     $facturas = Consultar::facturas('facturas', "*", "id", $p);
      $arreglo_factura['total_page'] = intval($facturas['total_page']);
      $arreglo_factura['page_actual'] = intval($facturas['page_actual']);
     //validamos
     if(!$facturas):
         echo '{"mensaje" : "No hay facturas registradas"}';
         http_response_code(400);
         return;
     endif;
       //completamos la info de las facturas
       $c = 0;
       foreach ($facturas['facturas'] as $factura):
        // print_r($factura);
           // Dibujamos array principal FACTURA
           $arreglo_factura['facturas'][$c] = [
               "id" => intval($factura['id']),
               "idUser" => intval($factura['id_cliente']),
               "codigo" => $factura['codigo'],
               "date" => $factura['fecha_pago'],
               "status" => $factura['tipo_entrega'],             
               "guia" => $factura['numero_envio'],
               "medioDePago" => $factura['metodo_pago'],
               "tiketPago" => $factura['num_comprobante']
           ];
         
           // Dibujamos el array del CARRITO
           $carrito = Consultar::datos($factura['codigo'], "carrito", "codigo", "plural");
           $countProduct = 0;
             foreach($carrito as $producto):
               //Dibujamos el array
               $arreglo_factura['facturas'][$c]['products'][$countProduct] = [
                   "id" => intval($producto['id_producto']),
                   "name" => $producto['nombre'],
                   "color" => $producto['color'],
                   "size" => $producto['talla'],
                   "img" => PUBLIC_PATH ."/img/". $producto['img'],
                   "quantity" => intval($producto['cantidad']),
                   "costo" => intval($producto['costo'])  
               ];
              $countProduct++;
           endforeach;
        $c++;
       endforeach;

      

       http_response_code(200);
       echo json_encode($arreglo_factura);
      
    }

    public function totalStock($codigoPadre)
    {
      $totalstock = ["totalStock" => Consultar::totalStock($codigoPadre)];
        //retornamos la lista de clientes
        http_response_code(200);
        echo json_encode($totalstock);
        return;
    }
    public function clientes()
    {
      // Consultamos los datos de los clientes
      $clientes = Consultar::datos(2, "usuarios", "tipo", "plural");

      //validamos
      if(!$clientes):
        echo '{"mensaje" : "No hay clientes"}';
        http_response_code(400);
      else:
        $lista;
        $count = 0;
        //Dibujamos el array
        foreach ($clientes as $cliente):
          $telefono = Consultar::fila($cliente['id'], "id_usuario", "telefono", "datos_entrega");
          
          $lista[] = [
            "id" => $cliente['id'],
            "name" => $cliente['nombres'],
            "email" => $cliente['correo'],
            "phone" => $telefono['telefono']           
          ];
          $count++;
        endforeach;

        //retornamos la lista de clientes
        http_response_code(200);
        echo json_encode($lista);
        return;

      endif;
    }

    public function productoCGP($param)
    {
      
      if (isset($param)) 
      {
        if ($productoData = Consultar::getProductoCGP($param, "productos")) 
        {
            //normalizamos la respuesta
            $repuesta = DataPage::productoCGP($productoData);
            http_response_code(200);
            //retornamos la respuesta
            echo json_encode($repuesta);
        } 
          else 
          {
            $respuestaCode = ["mensaje" => "El producto no existe"];
            http_response_code(400);
            echo json_encode($respuestaCode);
          }
      } 
      else 
      {
        $respuestaCode = ["mensaje" => "No hay parametros de busqueda"];
        http_response_code(404);
        echo json_encode($respuestaCode);
      }
  
    }

    public function productosAdmin(){
     
        $p = $_GET['page'] ?? 1;
      //consulta de la lista de producto
        $this->productos = Consultar::getProductosAdmin('productos', "id, nombre, costo, descuento, clic, stock, codigo, img, id_color", 'id', $p);
        $productosTodos = DataPage::productosAdmin($this->productos);
        http_response_code(200);
        echo json_encode($productosTodos);
    }
    //usuario
    public function misCompras($id)
    {
     
        //consulta de la lista de facturas
        $facturas = Consultar::datos($id,'facturas', "id_cliente", "plural");

        //validamos
        if(!$facturas):
            echo '{"mensaje" : "No hay compras generadas"}';
            http_response_code(400);
            return;
        endif;
          //completamos la info de las facturas
          $c = 0;
          foreach ($facturas as $factura):
              // Dibujamos array principal FACTURA
              $facturas[$c] = [
                  "id" => intval($factura['id']),
                  "codigo" => $factura['codigo'],
                  "date" => $factura['fecha_pago'],
                  "status" => $factura['tipo_entrega'],             
                  "guia" => $factura['numero_envio'],
                  "medioDePago" => $factura['metodo_pago'],
                  "tiketPago" => $factura['num_comprobante']
              ];
             
              // Dibujamos el array del CARRITO
              $carrito = Consultar::datos($factura['codigo'], "carrito", "codigo", "plural");
              $countProduct = 0;
                foreach($carrito as $producto):
                  //Dibujamos el array
                  $facturas[$c]['products'][$countProduct] = [
                      "id" => intval($producto['id']),
                      "name" => $producto['nombre'],
                      "color" => $producto['color'],
                      "size" => $producto['talla'],
                      "img" => PUBLIC_PATH ."/img/". $producto['img'],
                      "quantity" => intval($producto['cantidad']),
                      "costo" => intval($producto['costo']),
                      
                  ];
                  $countProduct++;
              endforeach;
              $c++;
          endforeach;
          http_response_code(200);
          echo json_encode($facturas);
    }
 
    public function detalleCompra($code)
    {
        $detalleCompra = DataPage::detalleCompra($code);
        echo json_encode($detalleCompra);
        http_response_code(200);
    }

    public function misDatos()
    {
      
      $misDatos = Consultar::datos($this->json->id_user, "usuarios", "id", "");
      $datosFactura = Consultar::datos($this->json->id_user, "datos_factura", "id_usuario", "");
      $datosEntrega = Consultar::datos($this->json->id_user, "datos_entrega", "id_usuario", "");
      
      if($misDatos == false):
        echo '{"mensaje" : "No hay datos registrados"}';
        http_response_code(404);
        return;
      else:
        $user;
        $count = 0;
        //Dibujamos el array
          $user['user'] = [
            "name"=>$misDatos['nombres'],
            "email"=>$misDatos['correo'],
           
          ];
          $user['user']['invoice'] = [
              "businessName" => $datosFactura['razon_social'],
              "departamento" => $datosFactura['departamento'],
              "provincia" => $datosFactura['provincia'],
              "distrito" => $datosFactura['distrito'],
              "direccion" => $datosFactura['direccion'],
              "documentType" => $datosFactura['tipo_documento'],
              "taxpayerType" => $datosFactura['tipo_contribuyente'],
          ];
          $user['user']['delivery'] = [
            "name" => $datosEntrega['nombre'],
            "departamento" => $datosEntrega['departamento'],
            "provincia" => $datosEntrega['provincia'],
            "distrito" => $datosEntrega['distrito'],
            "direccion" => $datosEntrega['direccion'],
            "referencia" => $datosEntrega['referencia'],
            "email" => $datosEntrega['correo']
          ];
        
        //retornamos la lista de clientes
        http_response_code(200);
        echo json_encode($user);
        return;

      endif;
      
    }

    public function buscador()
    {
        $resultados = Consultar::buscador($this->json->dato, "productos");  
        if(!$resultados){
          echo '{"mensaje": "No se encontro el producto."}';
          http_response_code(400);
        } else{
          echo json_encode($resultados['productos']);
          http_response_code(200);
        }    
 
    }



}//Cierre de la clases


	
 ?>
