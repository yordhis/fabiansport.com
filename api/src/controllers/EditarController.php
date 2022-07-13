<?php 
	namespace App\Controllers;

	use App\Views;
	use App\Models\Registrar;
	use App\Models\Consultar;
	use App\Models\Metodo;
  use App\Models\Actualizar;

	
	class EditarController 
	{
    protected $json;
    
    //Recibir JSON
		public function __construct()
		{
      if ($_SERVER["REQUEST_METHOD"] == "POST") 
      {
        if(isset($_POST['json'])):
          $this->json = json_decode($_POST['json'], true);
        else: 
          $this->json = null;
        endif;
      } 
    }
    
    // ACTUALIZAR CLIC DE PRODUCTOS
    public function clic($id_producto)
    {
      $dataClic = Consultar::getDato($id_producto, "productos", "clic", "id");
  
      $dataClic = $dataClic + 1;
      if(Actualizar::clic($dataClic, $id_producto)):
        http_response_code(200);
      else:
        http_response_code(404);
      endif;
    }
    // ACTUALIZAR CLIC DE TASA
    public function tasa()
    {
      $json = $this->json;
      if(Actualizar::tasa($json['id'], $json['tasa'])):
        http_response_code(200);
      else:
        http_response_code(400);
      endif;
    }

		// EDICION DE USUARIO
	
		public function usuario()
		{
      
        if(Actualizar::usuario($this->json)):
            
            if(Actualizar::datosFactura($this->json['billingData'], $this->json['id']) == false):
              echo json_encode(["mensaje" => "Los datos de factura no se actualizaron"]); 
            endif;
        
            if(Actualizar::datosEntrega($this->json['deliveryData'], $this->json['id']) == false):
              echo json_encode(["mensaje" => "Los datos de DELIVERYS no se actualizaron"]); 
            endif;
         
          //respuestas de la actualiacion
          http_response_code(200);
          
        else: 
          http_response_code(400);
          echo json_encode(["mensaje" => "La actualizacion no se ejecuto"]); 
        endif;

		}

		// ACTUALIZAR PRODUCTO
      // public function producto()
      // {
      //     // Variables
      // 		$formData = $this->json;
      //     $arreglo_producto;
      // 		$arreglo_tallas;
      //     $url = "../img/";
      //     $i = 0;
      //     $imagenes = $_FILES;
      //     $elementos = count($imagenes);
      //     $send_img = null;
      //       #normalizamos nombre de las imagenes
      //       if($elementos >0):
      //         $send_img = Metodo::normalizarImagen($imagenes);
      //           #consulto las imagnes existentes para agregarles las nuevas
      //             if($img_database = Consultar::fila($formData['id'], "id", "img", "productos")):
      //             else:$img_database['img'] = ""; 
      //             endif;
      //           #añadir imagenes del formulario a la de las database 
      //           if($img_database['img'] != ""): $img_database['img'] .=  "," . $send_img;
      //             else: $img_database['img'] = $send_img;
      //           endif; 
      //       else:
      //         #consulto las imagnes existentes para agregarles las nuevas
      //         if($img_database = Consultar::fila($formData['id'], "id", "img", "productos")):
      //         else:$img_database['img'] = ""; 
      //         endif;
      //       endif;    
      //       #areglo de los datos a editar
      //       $arreglo_producto = [
      //           "id" => $formData['id'],
      //           "codigo" => $formData['codigo'],
      //           "nombre" => $formData['name'],
      //           "genero" => $formData['filtro']['sex']['id'],
      //           "categoria" => $formData['filtro']['categoria']['id'],
      //           "marca" => $formData['filtro']['marca']['id'],
      //           "descuento" => $formData['descuento'],
      //           "costo" => $formData['costo'],
      //           "descripcion" => $formData['descripcion'],
      //           "caracteristicas" => $formData['caracteristicas'],
      //           "img" => $img_database['img'],
      //           "id_color" => $formData['id_color'],
      //           "mi_padre" => $formData['mi_padre']
      //       ];
        
      //     #verificamos si es padre o hijo ---------------------------
      //     if(Consultar::es_padre($formData['id'])):
      //         // verificamos que el color no se repita dentro de la familia
      //         if($colores = Consultar::datosRelacion(
      //           $arreglo_producto['mi_padre'], $arreglo_producto['id_color'],
      //           "mi_padre", "id_color", 'productos', '*', '')):
      //             if($colores['id'] != $formData['id']):
      //               echo '{"mensaje": "este color ya existe en tu familia de producto elije otro"}';
      //               return;
      //             endif;
      //         endif;

      //         //validamos que el color exista
      //         if(Consultar::getNombre($arreglo_producto['id_color'], "colores", "nombre") == false):
      //           echo '{"mensaje": "este color no existe en la base de datos"}';
      //           return; endif;

      //       // Actualizar producto
      //           if(Actualizar::producto($arreglo_producto)):else:
      //             $respuestaCode = ["mensaje" => "No se actualizo los datos del producto"];
      //             http_response_code(409);
      //             echo json_encode($respuestaCode);
      //             return;	
      //           endif;
                
      //           #actualizamos tallas
      //           foreach ($formData['sizes'] as $size): 
                
      //               $arreglo_talla = [
      //                 "nombre" => $size['name'],
      //                 "cantidad" => $size['quantity'],
      //                 "id" =>  $size['id'] ?? 0,
      //                 "codigo" =>  $arreglo_producto['codigo'],
      //                 "id_producto" =>  $arreglo_producto['id']
      //               ];
                    
      //               if(Actualizar::talla($arreglo_talla)):else: 
      //                   $respuesta = ["mensaje" => "No se actualizo la talla"];
      //                   http_response_code(400);
      //                   echo json_encode($respuesta);
      //                 return;
      //               endif; 
      //           endforeach;
              

      //           //MOVER LAS IMAGENES
      //             $img_name = explode(",",	$send_img);
      //               if($elementos >0):
      //                 for ($i=0; $i < $elementos ; $i++):
      //                   if(move_uploaded_file($imagenes[$i]["tmp_name"], $url.$img_name[$i])):else:
      //                     echo '{"mensajeBackEnd": "La imagen no se movio al directorio"}';
      //                     http_response_code(401);
      //                   endif;
      //                 endfor;
      //             endif;
                
      //           //ACTUALIZAR STOCK  
      //           if(Actualizar::stock($formData['id'])):
      //             echo '{"mensaje": "Actualizacion exitosa"}';
      //               http_response_code(200);
      //               else:
      //               echo '{"mensaje": "Actualizacion exitosa"}';
      //             http_response_code(200);
      //           endif;

      //     else: //NO ES PADRE -> ES HIJO 

      //          // verificamos que el color no se repita dentro de la familia
      //          if($colores = Consultar::datosRelacion(
      //             $arreglo_producto['mi_padre'], $arreglo_producto['id_color'],
      //             "mi_padre", "id_color", 'productos', '*', '')):
      //               if($colores['id'] != $formData['id']):
      //                 echo '{"mensaje": "este color ya existe en tu familia de producto elije otro"}';
      //                 return;
      //               endif;
      //           endif;
                
      //         // Actualizar producto hijo
      //          if(Actualizar::productoHijo($arreglo_producto)):else:
      //           echo '{"mensaje": "No se actualizo los datos del producto"}';
      //           http_response_code(409);
      //           return;	
      //         endif;

      //         //Actualizar tallas
      //         foreach ($formData['sizes'] as $size):
      //             $arreglo_talla = [
      //               "nombre" => $size['name'],
      //               "cantidad" => $size['quantity'],
      //               "id" =>  $size['id'] ?? 0,
      //               "codigo" =>  $formData['codigo'],
      //               "id_producto" =>  $formData['id']
      //             ];
      //             if(Actualizar::talla($arreglo_talla)):else: 
      //                 echo '{"mensajeBackEnd": "No se actualizo la talla"}';
      //                 http_response_code(400);
                      
      //               return;
      //             endif; 
      //         endforeach;

      //         //MOVER LAS IMAGENES
      //         $img_name = explode(",",	$send_img);
      //         if($elementos >0):
      //           for ($i=0; $i < $elementos ; $i++):
      //             if(move_uploaded_file($imagenes[$i]["tmp_name"], $url.$img_name[$i])):else:
      //               echo '{"mensajeBackEnd": "La imagen no se movio al directorio"}';
      //               http_response_code(401);
      //             endif;
      //           endfor;
      //         endif;

      //           //ACTUALIZAR STOCK  
      //           if(Actualizar::stock($formData['id'])):
      //             echo '{"mensaje": "Actualización exitosa"}';
      //               http_response_code(200);
      //               else:
      //               echo '{"mensaje": "Actualización exitosa"}';
      //             http_response_code(200);
      //           endif;
      //     endif;    
    // }

    // public function descuento()
      // {
      //     $costoActual = Consultar::datos($this->json['id'], "productos", "id", "", "costo");
      //     //Validamos que el descuento no sea mayor al costo
      //     if($costoActual['costo'] < $this->json['descount']):
      //       echo '{"mensaje": "El descuento no puede ser mayor al costo del producto"}';
      //       http_response_code(409);
      //       return;
      //     else:
      //       if(Actualizar::descuento($this->json) == false):
      //         echo '{"mensaje": "No se actualizo el descuento... Vuelve a intentar!"}';
      //         http_response_code(400);
      //       else:
              
      //         http_response_code(200);
      //       endif;
      //     endif;
    // }

    // public function entrega()
      // {
      //   if(Actualizar::entrega($this->json)):
      //     http_response_code(200);
      //   else:
      //     $respuesta = ["mensaje" => "Fallo la actualizacion de los datos de entrega"];
      //     http_response_code(400);
      //     echo json_encode($respuesta);
      //   endif;
    // }

	





		

	}//cierre de clase
