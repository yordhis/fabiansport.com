<?php
    namespace App\Models;

    use App\Models\{Producto, Ayudador, Img};
    

class Arreglar 
{
        //Arreglamos dato para JSON
        static function paraJsonSingular(array $datos){
          
                foreach ($datos as $key => $value){
                  if(!is_numeric($key)){ 
                    $valor = Arreglar::convertiraInt($key, $value);
                    if ($key == "clave" || $key == "token") continue;
                    if ($key == "tasa" && $value == 0) continue;
                    $arrayKeys[] = $key;
                    $arrayValores[] = $valor;                        
                  }       
                } 
                $arrayLimpio = array_combine($arrayKeys, $arrayValores);
                
          return $arrayLimpio;
        }

        //Arreglamos datos para JSON
        static function paraJsonPlural(array $datos){
          $vueltas = count($datos);
  
              for ($i=0; $i < $vueltas; $i++){ 
                foreach ($datos[$i] as $key => $value){
                  if(!is_numeric($key)){ 
                    $valor = Arreglar::convertiraInt($key, $value);
                    $arrayKeys[$i][] = $key;
                    $arrayValores[$i][] = $valor;                        
                  }       
                } 
                $arrayLimpio[] = array_combine($arrayKeys[$i], $arrayValores[$i]);
              }   
          if(!empty($arrayLimpio)){
            return $arrayLimpio;
          }else return null;   
        }

        //Arreglamos las url de las imagenes
        static function imgUrl($imagenes, $directorioEspesifico){
            if ($imagenes == "") return [];
            $imgCadenaTexto = null;
            $imgArrayNuevo = [];
            $imgArray = explode(',',$imagenes);
            
            foreach ($imgArray as $imgValue) {
              $imgCadenaTexto .= PUBLIC_PATH . '/' . $directorioEspesifico . '/' . $imgValue . ',';
            }
            $imgCadenaTexto = substr($imgCadenaTexto, 0,-1);
            $imgArrayNuevo = explode(',', $imgCadenaTexto);
            return $imgArrayNuevo;
        }

        //Arreglamos los datos del usuario para retornar un JSON
        static function usuario($datos, $comparador, $redSocial = ""){
          
          //retornamos la informacion del usuario
            $usuario = $datos;
            $datosEntrega = Ayudador::fila($usuario['id'], $comparador, "*", "datos_entrega");
            $datosFactura = Ayudador::fila($usuario['id'], $comparador, "*", "datos_factura");
            $likes = Ayudador::datos($usuario['id'], "favoritos", $comparador, "plural");

          //red social
          $avatar=null;
          if($redSocial == "google"){
            $avatar = $usuario['img_google'];
          }elseif($redSocial == "facebook"){
            $avatar = $usuario['img_facebook'];
          }else{
            $avatar = "";
          }
         
          //  $datosFactura = Consultar::datos($id, $comparador,"id_usuario", "*", "datos_factura");
          $usuarioDatos = [
            "id" => intval($usuario['id']),
            "name" => $usuario['nombres'],
            "email" => $usuario['correo'],
            "type" =>  intval($usuario['tipo']),
            "adult" =>   intval($usuario['adulto']), //true o false 
            "repeaInBilling" =>  intval($usuario['usar_dato_factura']), //true o false 
            "avatar" => $avatar
          ];
          if ($datosEntrega) {
            $usuarioDatos['deliveryData'] = [
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
            $usuarioDatos['billingData'] = [
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
          $info['user'] = $usuarioDatos;
          $info['like']=[];
          $q = 0;
          foreach ($likes as $like){
            $info['like'][$q] = [
              "idProduct" =>  intval($like['id_producto']),
              "idDad" =>  intval($like['id_dad']),
              "idUser" =>  intval($like['id_usuario']),
              "codigo" =>  $like['codigo'],
              "descuento" =>  $like['descuento'],
              "name" => $like['nombre'],
              "cost" => intval($like['costo']),
              "image" => $like['img']
            ];
            $q++;
          }
         
          return $info;
			
        }

        //Mis datos se muetrs los datos personales del cliente
        static function misDatos($misDatos){
          $user = [];
       
          //Dibujamos el array
            $user['user'] = [
              "idUser"=>$misDatos['id'],
              "name"=>$misDatos['nombres'],
              "email"=>$misDatos['correo'],
              "userType"=>intval($misDatos['tipo']),
              "avatarGoogle"=>$misDatos['img_google'],
              "avatarFacebook"=>$misDatos['img_facebook'],
              "adult"=>intval($misDatos['adulto']),
              "repeaInBilling"=>intval($misDatos['usar_dato_factura']),

            ];
            if (isset($misDatos['datosFactura'])) {
              $user['user']['billingData'] = [
                "idUser" => $misDatos['id'],
                "businessName" => $misDatos['datosFactura']['razon_social'],
                "departamento" => $misDatos['datosFactura']['departamento'],
                "province" => $misDatos['datosFactura']['provincia'],
                "distrito" => $misDatos['datosFactura']['distrito'],
                "direccion" => $misDatos['datosFactura']['direccion'],
                "documentType" => $misDatos['datosFactura']['tipo_documento'],
                "taxpayerType" => $misDatos['datosFactura']['tipo_contribuyente'],
              ];
            }
            if (isset($misDatos['datosEntrega'])) {
              $user['user']['deliveryData'] = [
                "idUser" => $misDatos['id'],
                "name" => $misDatos['datosEntrega']['nombre'],
                "departamento" => $misDatos['datosEntrega']['departamento'],
                "provincia" => $misDatos['datosEntrega']['provincia'],
                "distrito" => $misDatos['datosEntrega']['distrito'],
                "direccion" => $misDatos['datosEntrega']['direccion'],
                "reference" => $misDatos['datosEntrega']['referencia'],
                "email" => $misDatos['datosEntrega']['correo'],
                "phone" => $misDatos['datosEntrega']['telefono']
              ];
            }

            return $user;
        }

        static function productoDetalle($datosProductoPadre, $tipoUsuario){
          
          if(count($datosProductoPadre) > 0); 
          else return false;
          // if ($datosProductoPadre['stock'] == 0 && $tipoUsuario == "usuario") return "stop"; 
          //arreglamos las imagenes
          $img = Arreglar::imgUrl($datosProductoPadre['img'], 'img/productos');
          $color = Ayudador::datos($datosProductoPadre["id_color"], "colores", "id", "singular","hexadecimal");  
          $productoData =[
            "id" => intval($datosProductoPadre['id']),
            "codigo" => $datosProductoPadre['codigo'],
            "name" => $datosProductoPadre['nombre'],
            "descuento" => intval($datosProductoPadre['descuento']),
            "costo" => intval($datosProductoPadre['costo']),
            "descripcion" => $datosProductoPadre['descripcion'],
            "caracteristicas" => $datosProductoPadre['caracteristicas'],
            "es_padre" => intval($datosProductoPadre['es_padre']),
            "mi_padre" => $datosProductoPadre['mi_padre'],
            "id_color" => intval($datosProductoPadre["id_color"]),
            "hexadecimal" =>  $color['hexadecimal'],
            "name_color" => Ayudador::getNombre($datosProductoPadre["id_color"], "colores", "nombre"),  
            "image" => $img,
            "clic" => intval($datosProductoPadre['clic']),
            "stock" => intval($datosProductoPadre['stock']),
          ];
          $productoData["filtro"] = [
            "sex" => [
              "id" => intval($datosProductoPadre['id_genero']), 
              "name" => $datosProductoPadre['genero']
            ],

            "linea" => [
              "id" => intval($datosProductoPadre['id_linea']), 
              "name" => $datosProductoPadre['linea'] 
            ],

            "categoria" => [
              "id" => intval($datosProductoPadre['id_categoria']), 
              "name" => $datosProductoPadre['categoria'] 
            ],

            "marca" => [
              "id" => intval($datosProductoPadre['id_marca']), 
              "name" => $datosProductoPadre['marca'] 
            ]
          ];
          
           //consultamos las tallas del modelo 
           $tallas = Ayudador::datos($datosProductoPadre["id"], "tallas",
           "id_producto", "plural", "*", "id ASC");
         
           $vueltas = 0;
            foreach ($tallas as $talla){
              if ($talla['cantidad'] == 0 &&  $tipoUsuario == "usuario") continue;
             $productoData['sizes'][$vueltas] = [
                 "id" => intval($talla['id']),
                 "id_producto" => intval($talla['id_producto']),
                 "name" => $talla['nombre'],
                 "quantity" => intval($talla['cantidad'])
             ];
             $vueltas++;
            }
          // retornamos todo la informacion del producto 
          return $productoData;
        }

        static function productosAdmin($datosDeLosProductos){
          $productosData=[];
          $t = 0;
          
          if($datosDeLosProductos['productos'] == null):
            $productosData['productos'] = [];
            $productosData['totalPage'] = 1;
            $productosData['pageActual'] = 1;
            return  $productosData;
          endif; 
          
          //sino hacemos esto
          foreach ($datosDeLosProductos['productos'] as $producto):
           
            //arreglamos las imagenes
            $img = Arreglar::imgUrl($producto['img'], 'img/productos');
            $color = Ayudador::datos($producto["id_color"], "colores", "id", "singular","hexadecimal");  

            $productosData['productos'][$t] = [
              "id" => intval($producto['id']),
              "codigo" => $producto['codigo'],
              "name" => $producto['nombre'],
              "descuento" => intval($producto['descuento']),
              "costo" => intval($producto['costo']),
              "descripcion" => $producto['descripcion'],
              "caracteristicas" => $producto['caracteristicas'],
              "es_padre" => intval($producto['es_padre']),
              "mi_padre" => $producto['mi_padre'],
              "id_color" => intval($producto["id_color"]),
              "hexadecimal" => $color["hexadecimal"],
              "name_color" => Ayudador::getNombre($producto["id_color"], "colores", "nombre"),  
              "image" => $img,
              "clic" => intval($producto['clic']),
              "stock" => intval($producto['stock']),
              "filtro"=>[
                "sex" => [
                  "id" => intval($producto['id_genero']), 
                  "name" =>  Ayudador::getNombre($producto["id_genero"], "generos", "nombre") 
                ],
    
                "linea" => [
                  "id" => intval($producto['id_linea']), 
                  "name" => Ayudador::getNombre($producto["id_linea"], "lineas", "nombre") 
                ],
    
                "categoria" => [
                  "id" => intval($producto['id_categoria']), 
                  "name" => Ayudador::getNombre($producto["id_categoria"], "categorias", "nombre") 
                ],
    
                "marca" => [
                  "id" => intval($producto['id_marca']), 
                  "name" => Ayudador::getNombre($producto["id_marca"], "marcas", "nombre") 
                ]
              ]
            ];
              //consultamos las tallas del modelo 
              $tallas = Ayudador::datos($producto["id"], "tallas",
              "id_producto", "plural", "*", "id ASC");
            
              $vueltas = 0;
                foreach ($tallas as $talla){
                  $productosData['productos'][$t]['sizes'][$vueltas] = [
                    "id" => intval($talla['id']),
                    "id_producto" => intval($talla['id_producto']),
                    "name" => $talla['nombre'],
                    "quantity" => intval($talla['cantidad'])
                  ];
                $vueltas++;
              }
            $t++;
          endforeach;

          $productosData['totalPage'] = $datosDeLosProductos['totalPage'] ?? 1;
          $productosData['pageActual'] = $datosDeLosProductos['pageActual'] ?? 1;

          // retornamos todo la informacion del producto 
          return $productosData;
        }

        static function producto($codigoPadre){
          $arregloProductos=[];
            foreach($codigoPadre as $codigo){
              $producto = Producto::getProductoDetalle($codigo['codigo'], "productos", "usuario");
              if ($producto['stock'] > 0) {
                $arregloProductos[] = $producto;
              }
            }
          // retornamos todo la informacion del producto 
          return $arregloProductos;
        }

        static function productos($datos){
          $arregloProductos['productos']=[];

          //sino hacemos esto
          $contadorExterno = 0;
          foreach ($datos['codigosPadres'] as $codigoPadre){
          
            $familiaDeCodigo = Ayudador::query(" mi_padre =". "'{$codigoPadre['codigo']}'". " AND stock > 0", "productos","plural", "codigo","id ASC");
            $contadorInterno = 0;
            //sumamos todos los stock de la familia del producto para un 
            //stock general
            foreach($familiaDeCodigo as $codigo){
              // print_r($contadorExterno);
              $arregloProductos['productos'][$contadorExterno][$contadorInterno] = Producto::getProductoDetalle($codigo['codigo'], "productos", "usuario");
              
              $contadorInterno++;
            }
            
            // print_r("llegue");
            $contadorExterno++;
          }
            
          $arregloProductos['totalPage'] = $datos['totalPage'];
          $arregloProductos['pageActual'] = $datos['pageActual'];

          // retornamos todo la informacion del producto 
          return $arregloProductos;
        }

        static function detalleCompra($factura){
          $miCompra=[];

          // Dibujamos array principal FACTURA
          $miCompra = [
            "id" => intval($factura['id']),
            "codigo" => intval($factura['codigo']),
            "status" => $factura['tipo_entrega'],             
            "guia" => $factura['numero_envio'],
            "medioDePago" => $factura['metodo_pago'],
            "voucher" => PUBLIC_PATH . "/img/comprobantes/" . $factura['img'],
            "tiketPago" => $factura['num_comprobante'],
            "date" => $factura['fecha_pago'],
            "titular" => $factura['titular'],
            "pagado" => intval($factura['monto_pagado']),
            "mountUSD" => intval($factura['montoUSD']),
          ];
       
          //completamos la info de las facturas
          // Dibujamos array USER
          $user = Ayudador::datos($factura['id_cliente'], "usuarios", "id", "", "id,nombres, correo");
            $miCompra['user'] = [
              "id" => intval($user['id']),
              "name" => $user['nombres'],
              "email" => $user['correo']
            ];
         
            // Dibujamos el array del CARRITO
            $carritos = Ayudador::datos($factura['codigo'], "carritos", "codigo", "plural");
            if (empty($carritos)) {
              $miCompra['products'] = [];
            }else{
              $countProduct = 0;
                foreach($carritos as $producto){
                  $color = Ayudador::datos($producto["color"], "colores", "nombre", "singular","hexadecimal");  
                  //Dibujamos el array
                  $miCompra['products'][$countProduct] = [
                      "idCar" => intval($producto['id']),
                      "id" => intval($producto['id_producto']),
                      "code" => $producto['codigo'],
                      "name" => $producto['nombre'],
                      "color" => $producto['color'],
                      "hexadecimal" => $color['hexadecimal'],
                      "size" => $producto['talla'],
                      "img" => PUBLIC_PATH ."/img/productos/". $producto['img'],
                      "quantity" => intval($producto['cantidad']),
                      "costo" => intval($producto['costo']),
                      "sexo" => $producto['sexo'],
                      "categoria" => $producto['categoria'],
                      "marca" => $producto['marca']
                  ];
                  $countProduct++;
                }
            }
            // Dibujamos el array del DELIVERY
            $delivery = Ayudador::datos($factura['codigo'], "info_entrega", "codigo", "singular");
           
            if (empty($delivery)) {
              $miCompra['user']['deliveryData'] = [];
            }else{
              $miCompra['user']['deliveryData'] = [
              "code" => $delivery['codigo'],
              "name" => $delivery['nombre'],
              "departamento" => $delivery['departamento'],
              "provincia" => $delivery['provincia'],
              "distrito" => $delivery['distrito'],
              "direccion" => $delivery['direccion'],
              "reference" => $delivery['referencia'],
              "phone" => $delivery['telefono']
            ];
            }
        
            // Dibujamos el array del INVOICE
            $invoice = Ayudador::datos($factura['codigo'], "info_factura", "codigo", "singular");
            if (empty($invoice)) {
              $miCompra['user']['billingData'] = [];
            }else{
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
          }
          
          return $miCompra;
        }

        static function convertiraInt($key, $value){
          switch ($key) {
            case 'id':
            case 'id_usuario':
            case 'id_producto':
            case 'id_linea':
            case 'id_categoria':
            case 'id_marca':
            case 'id_genero':
            case 'id_favorito':
            case 'id_factura':
            case 'id_talla':
            case 'id_color':
            case 'id_cliente':
            case 'cantidad':
            case 'clic':
            case 'stock':
            case 'descuento':
                return intval($value);
              break;
          
            case 'costo':
                return floatval($value);
              break;

            default:
                return $value;
              break;

          }
        }

        static function facturas($facturas){
          //completamos la info de las facturas
          $arregloFacturas = [];
          $c = 0;
          foreach ($facturas as $factura){
            // print_r($factura);
            // Dibujamos array principal FACTURA
            $arregloFacturas[$c] = [
              "id" => intval($factura['id']),
              "idUser" => intval($factura['id_cliente']),
              "codigo" => $factura['codigo'],
              "date" => $factura['fecha_pago'],
              "status" => intval($factura['estatus']),
              "delivery" => $factura['tipo_entrega'],
              "guia" => $factura['numero_envio'],
              "medioDePago" => $factura['metodo_pago'],
              "tiketPago" => $factura['num_comprobante']
            ];

            // Dibujamos el array del CARRITO
            $carritos = Carrito::show($factura['codigo']);
            $contadorDeProducto = 0;
            if (empty($carritos)){
              $arregloFacturas[$c]['products'] = [];
            }else{
              foreach ($carritos as $producto){
                //Dibujamos el array
                $color = Ayudador::datos($producto["color"], "colores", "nombre", "singular","hexadecimal");  
                $arregloFacturas[$c]['products'][$contadorDeProducto] = [
                  "idCar" => intval($producto['id']),
                  "id" => intval($producto['id_producto']),
                  "name" => $producto['nombre'],
                  "color" => $producto['color'],
                  "hexadecimal" => $color['hexadecimal'],
                  "size" => $producto['talla'],
                  "img" => PUBLIC_PATH . "/img/productos/" . $producto['img'],
                  "quantity" => intval($producto['cantidad']),
                  "costo" => intval($producto['costo'])
                ];
                $contadorDeProducto++;
              }
             
            }
            $c++;
          }

          return $arregloFacturas;
        }
    

       

    }//cierre de clase
    


?>
