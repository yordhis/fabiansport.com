<?php
    namespace App\Models;

    use App\Models\Consultar;
    use App\Models\Metodo;

    class DataPage 
    {
        // cada metodo le pertece a una vista 
        //vista producto
        public function usuario($datos, $comparador, $rs = "")
        {
          
            //retornamos la informacion del usuario
          $user = $datos;
          $datosEntrega = Consultar::fila($user['id'], $comparador, "*", "datos_entrega");
          $datosFactura = Consultar::fila($user['id'], $comparador, "*", "datos_factura");
          $likes = Consultar::datos($user['id'], "favoritos", $comparador, "plural");

          //red social
          $avatar = null;
          if($rs == "google"):
            $avatar = $user['img_google'];
          elseif($rs == "facebook"):
            $avatar = $user['img_facebook'];
          else:
            $avatar = "";
          endif;
         
          //  $datosFactura = Consultar::datos($id, $comparador,"id_usuario", "*", "datos_factura");
          $userData = [
            "id" => intval($user['id']),
            "name" => $user['nombres'],
            "email" => $user['correo'],
            "type" =>  intval($user['tipo']),
            "adult" =>   intval($user['adulto']), //true o false 
            "repeaInBilling" =>  intval($user['usar_dato_factura']), //true o false 
            "avatar" => $avatar
          ];
          if ($datosEntrega) {
            $userData['deliveryData'] = [
              "name" => $datosEntrega['nombre'],
              "departamento" => $datosEntrega['departamento'],
              "provincia" => $datosEntrega['provincia'],
              "distrito" => $datosEntrega['distrito'],
              "direccion" => $datosEntrega['direccion'],
              "referencia" => $datosEntrega['referencia'],
              "phone" => $datosEntrega['telefono'],
            ];
          }
          if ($datosFactura) {
            $userData['billingData'] = [
              "email" => $datosEntrega['correo'],
              "razonSocial" => $datosFactura['razon_social'],
              "departemento" => $datosFactura['departamento'],
              "provincia" => $datosFactura['provincia'],
              "distrito" => $datosFactura['distrito'],
              "direccion" => $datosFactura['direccion'],
              "phone" => $datosEntrega['telefono'],
              "typeDocument" => $datosFactura['tipo_documento'],
              "tipoContribuyente" => $datosFactura['tipo_contribuyente'],
              "documentID" => $datosFactura['identificacion']
            ];
           
          }
          $info['user'] = $userData;

          $q = 0;
          foreach ($likes as $like):
            $nameColor = Consultar::getNombre($like['id_color'], "colores", "nombre");
            $info['like'][$q] = [
              "idProduct" =>  intval($like['id_producto']),
              "idUser" =>  intval($like['id_usuario']),
              "color" => [
                  "id" => intval($like['id_color']),
                  "name" => $nameColor
              ],
              "name" => $like['nombre'],
              "cost" => intval($like['costo']),
              "image" => $like['img']
            ];
            $q++;
          endforeach;
         
          return $info;
			
        }

        public function producto($datos)
        {
          
          $productoData =[
            "id" => intval($datos['id']),
            "codigo" => $datos['codigo'],
            "name" => $datos['nombre'],
            "descuento" => intval($datos['descuento']),
            "costo" => intval($datos['costo']),
            "descripcion" => $datos['descripcion'],
            "caracteristicas" => $datos['caracteristicas'],
            "es_padre" => intval($datos['es_padre']),
            "mi_padre" => $datos['mi_padre'],
          ];
          $productoData["filtro"] = [
            "sex" => [
              "id" => intval($datos['id_genero']), 
              "name" => $datos['genero'] 
            ],

            "categoria" => [
              "id" => intval($datos['id_categoria']), 
              "name" => $datos['categoria'] 
            ],

            "marca" => [
              "id" => intval($datos['id_marca']), 
              "name" => $datos['marca'] 
            ]
          ];
       
          $i = 0;
          foreach ($datos['modelos'] as $modelo):
           
                $productoData['modelos'][$i] = [
                    "id" => intval($modelo['id']),
                    "color"=> [
                      "id" => intval($modelo["id_color"]),
                      "name" => Consultar::getNombre($modelo["id_color"], "colores", "nombre"),  
                    ],           
                    "img" => explode(",", $modelo['img'])
                ];
                $z = 0;
              
                if($productoData['modelos'][$i]['img'][0] != ""):
                    foreach ($productoData['modelos'][$i]['img'] as $image):
                      $productoData['modelos'][$i]['img'][$z] = PUBLIC_PATH ."/img/". $image;
                      $z++; 
                    endforeach;
                  else:
                    $productoData['modelos'][$i]['img'] = [];
                endif;
            
                //consultamos las tallas del modelo 
                $tallas = Consultar::datos($modelo["id"], "tallas",
                "id_producto", "plural", "*", "id ASC");
              
                $c = 0;
                foreach ($tallas as $talla):
                  $productoData['modelos'][$i]['size'][$c] = [
                      "id" => intval($talla['id']),
                      "id_producto" => intval($talla['id_producto']),
                      "name" => $talla['nombre'],
                      "quantity" => intval($talla['cantidad'])
                  ];
                  $c++;
                endforeach;
            
            $i++;
          endforeach;
          
          // retornamos todo la informacion del producto 
          return $productoData;
        }

        public function productoCGP($datos)
        {
          //imagenes
          $images = explode(",", $datos['img']);
          $z = 0;
            if(count($images) > 0):
              foreach ($images as $image):
                $images_send[] = PUBLIC_PATH ."/img/". $image;
                $z++; 
              endforeach;
            else:
              $images_send = [];
            endif;


          $productoData =[
            "id" => intval($datos['id']),
            "codigo" => $datos['codigo'],
            "name" => $datos['nombre'],
            "descuento" => intval($datos['descuento']),
            "costo" => intval($datos['costo']),
            "descripcion" => $datos['descripcion'],
            "caracteristicas" => $datos['caracteristicas'],
            "es_padre" => intval($datos['es_padre']),
            "mi_padre" => $datos['mi_padre'],
            "id_color" => intval($datos["id_color"]),
            "name_color" => Consultar::getNombre($datos["id_color"], "colores", "nombre"),  
            "img" => $images_send
          ];
          $productoData["filtro"] = [
            "sex" => [
              "id" => intval($datos['id_genero']), 
              "name" => $datos['genero'] 
            ],

            "categoria" => [
              "id" => intval($datos['id_categoria']), 
              "name" => $datos['categoria'] 
            ],

            "marca" => [
              "id" => intval($datos['id_marca']), 
              "name" => $datos['marca'] 
            ]
          ];
          
           //consultamos las tallas del modelo 
           $tallas = Consultar::datos($datos["id"], "tallas",
           "id_producto", "plural", "*", "id ASC");
         
           $c = 0;
           foreach ($tallas as $talla):
             $productoData['sizes'][$c] = [
                 "id" => intval($talla['id']),
                 "id_producto" => intval($talla['id_producto']),
                 "name" => $talla['nombre'],
                 "quantity" => intval($talla['cantidad'])
             ];
             $c++;
           endforeach;
          // retornamos todo la informacion del producto 
          return $productoData;
        }

        public function productosAdmin($datos)
        {
          $productosData=[];
          $t = 0;
          
          if($datos['productos'] == null):
            $productosData['productos'] = [];
            $productosData['total_page'] = 1;
            $productosData['page_actual'] = 1;
            return  $productosData;
          endif; 
          
          //sino hacemos esto
          foreach ($datos['productos'] as $producto):
            // var_dump($producto); return;
             //imagenes
             $images_send = null;
             $images = explode(",", $producto['img']);
             $z = 0;
               if(count($images) > 0):
                 foreach ($images as $image):
                   $images_send[] = PUBLIC_PATH ."/img/". $image;
                   $z++; 
                 endforeach;
               else:
                 $images_send = [];
               endif;
// var_dump($images_send);
            $productosData['productos'][$t] = [
              "id" => intval($producto['id']) ?? '',
              "codigo" => $producto['codigo'] ?? '',
              "name" => $producto['nombre'] ?? '',
              "descuento" => intval($producto['descuento']) ?? '',
              "costo" => intval($producto['costo']) ?? '',
              //"modelos" => count(Consultar::getHijos($producto['codigo'])) ?? '',
              
              "image" => $images_send[0] ?? '',
              "stock" => intval($producto['stock'])
            ];
           
            $t++;
          endforeach;

          $productosData['total_page'] = $datos['total_page'];
          $productosData['page_actual'] = $datos['page_actual'];

          // retornamos todo la informacion del producto 
          return $productosData;
        }
        public function productos($datos)
        {
          $productosData=[];
          $t = 0;
          
          if($datos['productos'] == null):
            $productosData['productos'] = [];
            $productosData['total_page'] = 1;
            $productosData['page_actual'] = 1;
            return  $productosData;
          endif; 
          
          //sino hacemos esto
          foreach ($datos['productos'] as $producto):
             //imagenes
             $images_send = null;
             $images = explode(",", $producto['img']);
             $z = 0;
               if(count($images) > 0):
                 foreach ($images as $image):
                   $images_send[] = PUBLIC_PATH ."/img/". $image;
                   $z++; 
                 endforeach;
               else:
                 $images_send = [];
               endif;

            $productosData['productos'][$t] = [
              "id" => intval($producto['id']) ?? '',
              "codigo" => $producto['codigo'] ?? '',
              "name" => $producto['nombre'] ?? '',
              "descuento" => intval($producto['descuento']) ?? '',
              "costo" => intval($producto['costo']) ?? '',
              "modelos" => count(Consultar::getHijos($producto['codigo'])) ?? '',
              
              "image" => $images_send[0] ?? '',
              "stock" => intval($producto['stock'])
            ];
           
            $t++;
          endforeach;

          $productosData['total_page'] = $datos['total_page'];
          $productosData['page_actual'] = $datos['page_actual'];

          // retornamos todo la informacion del producto 
          return $productosData;
        }

        public function detalleCompra($code)
        {
          $miCompra=[];

          $factura = Consultar::datos($code,"facturas", "codigo", "");
           //validamos
            if(!$factura):
              return ["mensaje" => "Este codigo de factura no existe"];
            endif;
          // Dibujamos array principal FACTURA
          $miCompra = [
            "id" => intval($factura['id']),
            "codigo" => intval($factura['codigo']),
            "status" => $factura['tipo_entrega'],             
            "guia" => $factura['numero_envio'],
            "medioDePago" => $factura['metodo_pago'],
            "voucher" => PUBLIC_PATH . "/img/comprobantes/" . $factura['comprobante'],
            "tiketPago" => $factura['num_comprobante'],
            "date" => $factura['fecha_pago'],
            "titular" => $factura['titular'],
            "pagado" => intval($factura['monto_pagado']),
            "mountUSD" => intval($factura['montoUSD']),
          ];
       
          //completamos la info de las facturas
          // Dibujamos array USER
          $user = Consultar::datos($factura['id_cliente'], "usuarios", "id", "", "id,nombres, correo");
            $miCompra['user'] = [
              "id" => intval($user['id']),
              "name" => $user['nombres'],
              "email" => $user['correo']
            ];
          // Dibujamos el array del CARRITO
            $carrito = Consultar::datos($factura['codigo'], "carrito", "codigo", "plural");
            $countProduct = 0;
              foreach($carrito as $producto):
                //Dibujamos el array
                $miCompra['products'][$countProduct] = [
                    "id" => intval($producto['id_producto']),
                    "code" => $producto['codigo'],
                    "name" => $producto['nombre'],
                    "color" => $producto['color'],
                    "size" => $producto['talla'],
                    "img" => PUBLIC_PATH ."/img/". $producto['img'],
                    "quantity" => intval($producto['cantidad']),
                    "costo" => intval($producto['costo']),
                    "sexo" => $producto['sexo'],
                    "categoria" => $producto['categoria'],
                    "marca" => $producto['marca']
                ];
                $countProduct++;
              endforeach;
          
            // Dibujamos el array del DELIVERY
            $delivery = Consultar::datos($factura['codigo'], "info_entrega", "codigo", "");
            $miCompra['user']['deliveryData'] = [
              "code" => $delivery['codigo'],
              "name" => $delivery['nombre'],
              "departamento" => $delivery['departamento'],
              "provincia" => $delivery['provincia'],
              "distrito" => $delivery['distrito'],
              "direccion" => $delivery['direccion'],
              "referencia" => $delivery['referencia'],
              "phone" => $delivery['telefono']
            ];
        
            // Dibujamos el array del INVOICE
            $invoice = Consultar::datos($factura['codigo'], "info_factura", "codigo", "");
            $miCompra['user']['billingData'] = [
              "code" => $invoice['codigo'],
              "email" => $invoice['correo'],
              "razonSocial" => $invoice['razon_social'],
              "departamento" => $invoice['departamento'],
              "provincia" => $invoice['provincia'],
              "distrito" => $invoice['distrito'],
              "direccion" => $invoice['direccion'],
              "typeDocument" => $invoice['tipo_documento'],
              "tipoContribuyente" => $invoice['tipo_contribuyente'],
              "documentID" => $invoice['identificacion'],
              "phone" => $invoice['telefono']
            ];
          
          return $miCompra;
        }

        

       

       

    }//cierre de clase
    


?>
