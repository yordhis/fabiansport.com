<?php
    namespace App\Models;

    use App\Models\Consultar;
    use App\Models\Registrar;
    
    class Actualizar 
    {

        public function estatusFactura($code, $estatus = 1)
        {
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE facturas SET estatus = :estatus
          WHERE codigo = :codigo ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':estatus', $estatus);
					$sentencia->bindParam(':codigo', $code);
          $sentencia->execute();

					if($sentencia->rowCount()):return true; else:return false; endif;
				
        }
        
        public function clic($clic, $id)
        {
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE productos SET clic = :clic
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':clic', $clic);
					$sentencia->bindParam(':id', $id);
          $sentencia->execute();
					if($sentencia->rowCount()):return true; else:return false; endif;
				
        }
        public function tasa($id_admin, $tasa)
        {
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE usuarios SET tasa = :tasa
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':id', $id_admin);
					$sentencia->bindParam(':tasa', $tasa);
          $sentencia->execute();
					if($sentencia->rowCount()):return true; else:return false; endif;
				
        }

        public function descuento($datos)
        {
          include (APP_PATH . 'config/database.php');

          //Actualizar DESCUENTO 
					$sql =  "UPDATE productos SET descuento = :descuento
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':id', $datos['id']);
					$sentencia->bindParam(':descuento', $datos['descount']);
          $sentencia->execute();
					if($sentencia->rowCount()):return true; else:return false; endif;
				
        }
        public function usuarioFactura($datos)
        {
          include (APP_PATH . 'config/database.php');

          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET  adulto = :adulto,
          usar_dato_factura = :usar_dato_factura
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':id', $datos->idUser);
          $sentencia->bindParam(':adulto', $datos->adult);
					$sentencia->bindParam(':usar_dato_factura', $datos->repeaInBilling);

					if($sentencia->execute())
					{
						return true;
					}
					else
					{	
						return false;
					}
        }
        public function usuario($datos)
        {
          include (APP_PATH . 'config/database.php');

          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET nombres = :nombres, adulto = :adulto,
          usar_dato_factura = :usar_dato_factura
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':id', $datos['id']);
					$sentencia->bindParam(':nombres', $datos['name']);
          $sentencia->bindParam(':adulto', $datos['adult']);
					$sentencia->bindParam(':usar_dato_factura', $datos['repeaInBilling']);

					if($sentencia->execute())
					{
						return true;
					}
					else
					{	
						return false;
					}
        }

        public function token($token, $correo)
        {
          include (APP_PATH . 'config/database.php');

          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET token = :token
          WHERE correo = :correo ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':correo', $correo);
					$sentencia->bindParam(':token', $token);
          $sentencia->execute();
					if($sentencia->rowCount()):
						return true;
          else:
						return false;
					endif;
        }

        public function clave($clave, $correo)
        {
          include (APP_PATH . 'config/database.php');

          $clave = md5($clave);

          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET clave = :clave
          WHERE correo = :correo ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':correo', $correo);
					$sentencia->bindParam(':clave', $clave);
          $sentencia->execute();
					if($sentencia->rowCount()):
						return true;
          else:
						return false;
					endif;
        }
        //Actualizar avatar o id de redes sociales
        public function avatar($datos, $redSocial = "id_facebook", $avatar = "img_facebook", $idrs = "facebookId")
        {
          include (APP_PATH . 'config/database.php');
          //print_r($datos->imageUrl);
          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET $redSocial = :redSocial, $avatar = :avatar
          WHERE correo = :correo ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':correo', $datos->email);
					$sentencia->bindParam(':redSocial', $datos->$idrs);
          $sentencia->bindParam(':avatar', $datos->imageUrl);
          
          //$sentencia->rowCount();
          if($sentencia->execute()): 
            return true;
					else:
						return false;
          endif;

        }
        //Actualizacion de Producto HIJO esto es cuando se actualiza el hijo directamente
        public function productoHijo($datos)
        {
          include (APP_PATH . 'config/database.php');

         
              $sql = "UPDATE productos SET img = :img, id_color = :id_color WHERE id = :id";
              $sentencia = $pdo->prepare($sql);
              $sentencia->bindParam(':id', $datos['id']);             
              $sentencia->bindParam(':img', $datos['img']);
              $sentencia->bindParam(':id_color', $datos['id_color']);

              if($sentencia->execute()): return true; else: return false; endif;
        }
        //Actualizacion de Producto PADRE y heredar los nuevos atributos a los hijos 
        public function herenciaAdn($datos, $codigoPadre)
        {
          include (APP_PATH . 'config/database.php');
              $num1 = 1;
              $num2 = 0;
              $sql = "UPDATE productos SET  nombre = :nombre, 
              id_marca = :id_marca, id_categoria = :id_categoria, id_genero = :id_genero, 
              descuento = :descuento, costo = :costo, descripcion = :descripcion,
              caracteristicas = :caracteristicas, id_descuento = :id_descuento, 
              oferta = :oferta
              WHERE mi_padre = :mi_padre ";
              
                  $sentencia = $pdo->prepare($sql);
                //BINCULACION DE DATOS
                  $sentencia->bindParam(':mi_padre', $datos['codigo']);
                  $sentencia->bindParam(':nombre', $datos['nombre']);
                  $sentencia->bindParam(':id_marca', $datos['marca']);
                  $sentencia->bindParam(':id_categoria', $datos['categoria']);
                  $sentencia->bindParam(':id_genero', $datos['genero']);
                  $sentencia->bindParam(':descuento', $datos['descuento']); 
                  
                  if ($datos['descuento'] > 0) {
                    $sentencia->bindParam(':id_descuento', $num1);	
                    $sentencia->bindParam(':oferta', $num1);
                  }
                  else{
                    $sentencia->bindParam(':id_descuento', $num2);	
                    $sentencia->bindParam(':oferta', $num2);
                  }
              
                  $sentencia->bindParam(':costo', $datos['costo']);
                  $sentencia->bindParam(':descripcion', $datos['descripcion']);
                  $sentencia->bindParam(':caracteristicas', $datos['caracteristicas']);
                // CIERRE DE VINCULACION
                  if($sentencia->execute()): return true;  
                  else: return false; endif;
        }
        //Actualizacion de Producto PADRE
        public function Actualizar($datos)
        {
          include (APP_PATH . 'config/database.php');

              $num1 = 1;
              $num2 = 0;
              $sql = "UPDATE productos SET  nombre = :nombre, 
              id_marca = :id_marca, id_categoria = :id_categoria, id_genero = :id_genero, 
              descuento = :descuento, costo = :costo, descripcion = :descripcion,
              caracteristicas = :caracteristicas, id_descuento = :id_descuento, 
              oferta = :oferta, img = :img, id_color = :id_color
              WHERE id = :id ";
              
                  $sentencia = $pdo->prepare($sql);
                  $sentencia->bindParam(':id', $datos['id']);
                  //BINCULACION DE DATOS
                    $sentencia->bindParam(':nombre', $datos['nombre']);
                    $sentencia->bindParam(':id_marca', $datos['marca']);
                    $sentencia->bindParam(':id_categoria', $datos['categoria']);
                    $sentencia->bindParam(':id_genero', $datos['genero']);
                    $sentencia->bindParam(':descuento', $datos['descuento']); 
                    
                    if ($datos['descuento'] > 0) {
                      $sentencia->bindParam(':id_descuento', $num1);	
                      $sentencia->bindParam(':oferta', $num1);
                    }
                    else{
                      $sentencia->bindParam(':id_descuento', $num2);	
                      $sentencia->bindParam(':oferta', $num2);
                    }
                
                    $sentencia->bindParam(':costo', $datos['costo']);
                    $sentencia->bindParam(':descripcion', $datos['descripcion']);
                    $sentencia->bindParam(':caracteristicas', $datos['caracteristicas']);
                    $sentencia->bindParam(':img', $datos['img']);
                    $sentencia->bindParam(':id_color', $datos['id_color']);
                  // CIERRE DE VINCULACION

                  if($sentencia->execute()): 
                    if(Actualizar::herenciaAdn($datos, $datos['codigo'])):
                      return true;
                    else: echo '{"mensajeBackEnd": "Los hijos no heredaron el ADN"}'; endif;
                  else: return false; endif;
        }
        
        //Actualizacion de Producto
        public function talla($datos)
        {
          include (APP_PATH . 'config/database.php');

          if($datos['id'] > 0):

            $sql = "UPDATE tallas SET nombre = :nombre, 
            cantidad = :cantidad
            WHERE id = :id ";
            
                $sentencia = $pdo->prepare($sql);
      
                $sentencia->bindParam(':nombre', $datos['nombre']);
                $sentencia->bindParam(':cantidad', $datos['cantidad']);
                $sentencia->bindParam(':id', $datos['id']);
          
                if($sentencia->execute()): return true; else: return false; endif;
          else:
            if(Consultar::datosRelacion($datos['id_producto'], $datos['nombre'], "id_producto", "nombre", 'tallas', '*', '')):
              echo '{"mensajeBackEnd": "esta talla ya existe continuare con la otra"}';
              return true; endif;
            if(Registrar::talla($datos)): return true;
            else: return false; endif;
          endif;
        }
       
        // Actualizar heredero (PADRE)
        public function heredero($codigoHijo, $codigoPadre){
          include (APP_PATH . 'config/database.php');

            $sql = "UPDATE productos SET mi_padre = :codigoHijo
            WHERE mi_padre = :codigoPadre";

            $sentencia = $pdo->prepare($sql);
                  
            $sentencia->bindParam(':codigoPadre', $codigoPadre);
            $sentencia->bindParam(':codigoHijo', $codigoHijo);
            $sentencia->execute();
            if($sentencia->rowCount()): return true; 
            else: return false; endif;
        }

          //Asignar el nuevo padre
          public function nuevoPadre($idHijo){
            include (APP_PATH . 'config/database.php');

            $sql = "UPDATE productos SET es_padre = 1
            WHERE id = :idHijo";

            $sentencia = $pdo->prepare($sql);
                  
            $sentencia->bindParam(':idHijo', $idHijo);
            $sentencia->execute();
            if($sentencia->rowCount()): return true; 
            else: return false; endif;
          }

         //Actualizacion de datos de entrega (tarjeta de envio)
         public function datosEntrega($datos, $id_usuario)
         {
         
           include(APP_PATH . 'config/database.php');
       
           $sql = "UPDATE datos_entrega SET  nombre = :nombre, 
           departamento = :departamento, provincia = :provincia,
           distrito = :distrito, direccion = :direccion,
           referencia = :referencia, telefono = :telefono
           WHERE id_usuario = :id_usuario ";
           
               $sentencia = $pdo->prepare($sql);
     
               $sentencia->bindParam(':id_usuario', $id_usuario);
               $sentencia->bindParam(':nombre', $datos['name']);
               $sentencia->bindParam(':departamento', $datos['departamento']); 
               $sentencia->bindParam(':provincia', $datos['provincia']); 
               $sentencia->bindParam(':distrito', $datos['distrito']); 
               $sentencia->bindParam(':direccion', $datos['direccion']); 
               $sentencia->bindParam(':referencia', $datos['referencia']); 
               $sentencia->bindParam(':telefono', $datos['phone']); 
   
               if($sentencia->execute())
               {
                 return true;
               }
               else
               {	
                 return false;
               }
         }

         //Actualizacion de datos de Factura (tarjeta de recibo de pago)
         public function datosFactura($datos, $id_usuario)
         {
           include (APP_PATH . 'config/database.php');
           
           $sql = "UPDATE datos_factura SET correo = :correo, 
           razon_social = :razon_social, departamento = :departamento, 
           provincia = :provincia, distrito = :distrito, 
           direccion = :direccion, tipo_documento = :tipo_documento, telefono = :telefono, 
           tipo_contribuyente = :tipo_contribuyente, identificacion = :identificacion 
           WHERE id_usuario = :id_usuario ";
           
               $sentencia = $pdo->prepare($sql);
     
               $sentencia->bindParam(':id_usuario', $id_usuario);
               $sentencia->bindParam(':correo', $datos['email']);
               $sentencia->bindParam(':razon_social', $datos['razonSocial']);
               $sentencia->bindParam(':departamento', $datos['departamento']); 
               $sentencia->bindParam(':provincia', $datos['provincia']); 
               $sentencia->bindParam(':distrito', $datos['distrito']); 
               $sentencia->bindParam(':direccion', $datos['direccion']); 
               $sentencia->bindParam(':telefono', $datos['phone']); 
               $sentencia->bindParam(':tipo_documento', $datos['typeDocument']); 
               $sentencia->bindParam(':tipo_contribuyente', $datos['tipoContribuyente']); 
               $sentencia->bindParam(':identificacion', $datos['documentID']); 
                           
               if($sentencia->execute())
               {
                 return true;
               }
               else
               {	
                 return false;
               }
         }


        public function stock($id)
        {
          $cantidad = Consultar::getTallaCantidad($id, "tallas");

            include (APP_PATH . 'config/database.php');
    
            $sql = "UPDATE productos SET stock = '".$cantidad."' WHERE id ='".$id."'
            OR  codigo ='".$id."' ";
            $sentencia = $pdo->prepare($sql);         
            $sentencia->execute();
              if($sentencia->rowCount()):
                return true; 
              else:
                return false;
              endif;         
        }
        
        public function entrega($datos)
        {
            include (APP_PATH . 'config/database.php');
    
            $sql = "UPDATE facturas SET 
            numero_envio = :guia,
            tipo_entrega = :tipoEntrega 
            WHERE id = :id ";
            $sentencia = $pdo->prepare($sql); 
           
            $sentencia->bindParam(":guia", $datos['guia']);        
            $sentencia->bindParam(":tipoEntrega", $datos['status']);        
            $sentencia->bindParam(":id", $datos['id']); 

            $sentencia->execute();
            
              if($sentencia->rowCount() > 0):
                return true; 
              else:
                return false;
              endif;         
        }

        public function img($id, $imagenes)
        {
            include (APP_PATH . 'config/database.php');
          
            $sql = "UPDATE productos SET img = '".$imagenes."' WHERE id ='".$id."' ";
            $sentencia = $pdo->prepare($sql);         
            $sentencia->execute();
              if($sentencia->rowCount()):
                return true; 
              else:
                return false;
              endif;         
        }

        
  //Fin de objeto     
    }
?>
