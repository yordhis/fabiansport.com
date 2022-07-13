<?php 
	namespace App\Models;

  use App\Models\Metodo;
  use App\Models\Consultar;
  use App\Models\Actualizar;
	
	class Registrar 
	{
    public function carrito($datos, $codigo){
      include (APP_PATH . 'config/database.php');
      $sql =" INSERT INTO carrito
      (codigo, id_producto, id_talla, cantidad, costo, nombre, img, talla, color,
      sexo, categoria, marca) 
      VALUES 
      (:codigo, :id_producto, :id_talla, :cantidad, :costo, :nombre, :img, :talla, :color,
      :sexo, :categoria, :marca)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':codigo', $codigo);
      $sentencia->bindParam(':id_producto', $datos->id);
      $sentencia->bindParam(':id_talla', $datos->size->id);
      $sentencia->bindParam(':cantidad', $datos->quantity);
      $sentencia->bindParam(':costo', $datos->costo);
      $sentencia->bindParam(':nombre', $datos->name);
      $sentencia->bindParam(':img', $datos->image);
      $sentencia->bindParam(':talla', $datos->size->name);
      $sentencia->bindParam(':color', $datos->color->name);
      $sentencia->bindParam(':sexo', $datos->sex);
      $sentencia->bindParam(':categoria', $datos->categoria);
      $sentencia->bindParam(':marca', $datos->marca);
      
      //Ejecutamos todo
      if($sentencia->execute()): return true; else: return false; endif;
    }

    public function factura($datos)
    {
      include (APP_PATH . 'config/database.php');

      
      $sql =" INSERT INTO facturas(codigo, id_cliente, metodo_pago, comprobante, num_comprobante, fecha_pago, monto_pagado, titular, mayoria_edad, montoUSD)
      VALUES( :codigo, :id_cliente, :metodo_pago, :comprobante, 
      :num_comprobante, :fecha_pago, :monto_pagado,  
      :titular, :mayoria_edad, :montoUSD) ";

      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':codigo', $datos->code);
      $sentencia->bindParam(':id_cliente', $datos->idUser);
      $sentencia->bindParam(':metodo_pago', $datos->typePayment);
      $sentencia->bindParam(':comprobante', $datos->image);
      $sentencia->bindParam(':num_comprobante', $datos->refPayment);
      $sentencia->bindParam(':fecha_pago', $datos->date);
      $sentencia->bindParam(':monto_pagado', $datos->mount);
      $sentencia->bindParam(':titular', $datos->titular);
      $sentencia->bindParam(':mayoria_edad', $datos->isAdult);
      $sentencia->bindParam(':montoUSD', $datos->mountUSD);
 
      
      //Ejecutamos todo
      if($sentencia->execute()): return true; else: return false; endif;
    }

    public function color($datos)
    {
      include (APP_PATH . 'config/database.php');
            
      $sql =  "INSERT INTO colores (nombre) 
      VALUES(:nombre)";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':nombre', $datos);

      if($sentencia->execute()): return true; else: return false; endif;
    }
    // REGISTRO DE FAVORITOS
		public function favorito($datos)
		{
			include (APP_PATH . 'config/database.php');
      $sql =  "INSERT INTO favoritos (id_usuario, id_producto, nombre, costo, id_color, img) 
      VALUES (:id_usuario, :id_producto, :nombre, :costo, :id_color, :img)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':id_usuario', $datos->idUser);
      $sentencia->bindParam(':id_producto', $datos->idProduct);
      $sentencia->bindParam(':nombre', $datos->name);
      $sentencia->bindParam(':costo', $datos->cost);
      $sentencia->bindParam(':id_color', $datos->color->id);
      $sentencia->bindParam(':img', $datos->image);
            
      if($sentencia->execute()): return true; else: return false; endif;
		}
		// REGISTRO DE USUARIO CON RED SOCIAL
		public function redSocial($datos)
		{ 
      include (APP_PATH . 'config/database.php');
        
      $sql =  "INSERT INTO usuarios (nombres, correo, id_google, id_facebook, img_google, img_facebook) 
      VALUES(:nombres, :correo, :id_google, :id_facebook, :img_google, :img_facebook)";

      $sentencia = $pdo->prepare($sql);

      //validamos si los ids de las redes existen o sino asignar 0
      $google = $datos->googleId ?? 0;
      $facebook = $datos->facebookId ?? 0;
      $avatarG = "";
      $avatarF = "";
      //validamos la imagen del avatar
      if($google != 0):
        $avatarG = $datos->imageUrl;
      elseif($facebook != 0):
          $avatarF = $datos->imageUrl;    
      endif;

      $sentencia->bindParam(':nombres', $datos->name);
      $sentencia->bindParam(':correo', $datos->email);
      $sentencia->bindParam(':id_facebook', $facebook);
      $sentencia->bindParam(':id_google', $google);
      $sentencia->bindParam(':img_google', $avatarG);
      $sentencia->bindParam(':img_facebook', $avatarF);
      
      if($sentencia->execute()): return true; else: return false; endif;
    }
    
    // REGISTRO DE DATOS DE ENTREGA RS
    public function datosEntregaRS($datos)
    {
      include (APP_PATH . 'config/database.php');
      //Registro de datos de entrega
      $sql =  "INSERT INTO datos_entrega (nombre ,correo, id_usuario) 
      VALUES(:nombre, :correo, :id_usuario)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':nombre', $datos->name);
      $sentencia->bindParam(':correo', $datos->email);
      $sentencia->bindParam(':id_usuario', $datos->id);
  
      if($sentencia->execute()): return true; else: return false; endif;
    }

    // REGISTRO DE DATOS DE ENTREGA RS
    public function datosFacturaRS($datos)
    {
      include (APP_PATH . 'config/database.php');
      //Registro de datos de Factura
      $sql =  "INSERT INTO datos_factura (correo, id_usuario) 
      VALUES(:correo, :id_usuario)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':correo',  $datos->email);
      $sentencia->bindParam(':id_usuario', $datos->id);
      
      if($sentencia->execute()): return true; else: return false; endif;
    }

		// REGISTRO DE USUARIO
		public function usuario($datos)
		{ 
      include (APP_PATH . 'config/database.php');
      $pass = md5($datos->password);     
      $sql =  "INSERT INTO usuarios (correo, clave) VALUES(:correo, :clave)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':correo', $datos->email);
      $sentencia->bindParam(':clave', $pass);
            
      if($sentencia->execute()): return true; else: return false; endif;
		}
    // REGISTRO DE DATOS DE ENTREGA
    public function datosEntrega($datos)
    {
      include (APP_PATH . 'config/database.php');
      //Registro de datos de entrega
      $sql =  "INSERT INTO datos_entrega (correo, id_usuario) 
      VALUES(:correo, :id_usuario)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':correo', $datos->email);
      $sentencia->bindParam(':id_usuario', $datos->id);

      if($sentencia->execute()): return true; else: return false; endif;
    }
    // REGISTRO DE DATOS DE ENTREGA
    public function datosFactura($datos)
    {
      include (APP_PATH . 'config/database.php');
      //Registro de datos de Factura
      $sql =  "INSERT INTO datos_factura (correo, id_usuario) 
      VALUES(:correo, :id_usuario)";

      $sentencia = $pdo->prepare($sql);

      $sentencia->bindParam(':correo',  $datos->email);
      $sentencia->bindParam(':id_usuario', $datos->id);

      if($sentencia->execute()): return true; else: return false; endif;
    }

     //Registrar INFO de entrega (tarjeta de envio)
     public function infoEntrega($datos, $codigo)
     {
       include (APP_PATH . 'config/database.php');

       $sql = "INSERT INTO info_entrega 
       (codigo, nombre, departamento,
        provincia, distrito, direccion,
        referencia, telefono ) VALUES (:codigo, :nombre, :departamento,
        :provincia, :distrito, :direccion,
        :referencia, :telefono)
      ";
       
           $sentencia = $pdo->prepare($sql);
 
           $sentencia->bindParam(':codigo', $codigo);
           $sentencia->bindParam(':nombre', $datos->name);
           $sentencia->bindParam(':departamento', $datos->departamento); 
           $sentencia->bindParam(':provincia', $datos->provincia); 
           $sentencia->bindParam(':distrito', $datos->distrito); 
           $sentencia->bindParam(':direccion', $datos->direccion); 
           $sentencia->bindParam(':referencia', $datos->referencia); 
           $sentencia->bindParam(':telefono', $datos->phone); 
                       
           if($sentencia->execute()): return true; else: return false; endif;
     }

     //Registrar INFO de Factura (tarjeta de recibo de pago)
     public function infoFactura($datos, $codigo)
     {
       include (APP_PATH . 'config/database.php');
       
       $sql = "INSERT INTO info_factura
       (codigo, correo, razon_social, departamento,
        provincia, distrito, direccion, tipo_documento, 
        tipo_contribuyente, identificacion, telefono) 
        VALUES (:codigo, :correo, :razon_social, :departamento,
        :provincia, :distrito, :direccion, :tipo_documento, 
        :tipo_contribuyente, :identificacion, :telefono)";
       
           $sentencia = $pdo->prepare($sql);
 
           $sentencia->bindParam(':codigo', $codigo);
           $sentencia->bindParam(':correo', $datos->email);
           $sentencia->bindParam(':razon_social', $datos->razonSocial);
           $sentencia->bindParam(':departamento', $datos->departamento); 
           $sentencia->bindParam(':provincia', $datos->provincia); 
           $sentencia->bindParam(':distrito', $datos->distrito); 
           $sentencia->bindParam(':direccion', $datos->direccion); 
           $sentencia->bindParam(':tipo_documento', $datos->typeDocument); 
           $sentencia->bindParam(':tipo_contribuyente', $datos->tipoContribuyente);
           $sentencia->bindParam(':identificacion', $datos->documentID);
           $sentencia->bindParam(':telefono', $datos->phone); 
                       
           if($sentencia->execute()): return true; else: return false; endif;
     }
   
		// REGISTRO DE PRODUCTOS - INVENTARIO
		public function producto($producto){
			include (APP_PATH . 'config/database.php');
			$num1 = 1;
			$num2 = 0;

			$sql = "INSERT INTO productos (codigo, nombre, id_marca, id_linea, id_categoria, 
                                    id_genero, id_descuento, descuento, costo, 
                                    descripcion, caracteristicas, img, es_padre, 
                                    mi_padre, id_color, oferta) 
                            VALUES (:codigo, :nombre, :id_marca, :id_linea, :id_categoria, 
                                    :id_genero, :id_descuento, :descuento, :costo, 
                                    :descripcion, :caracteristicas, :img, :es_padre,
                                    :mi_padre, :id_color, :oferta)";
                  
					$sentencia = $pdo->prepare($sql);

					$sentencia->bindParam(':codigo', $producto['codigo']);
					$sentencia->bindParam(':nombre', $producto['nombre']);
					$sentencia->bindParam(':id_marca', $producto['marca']);
					$sentencia->bindParam(':id_linea', $producto['linea']);
					$sentencia->bindParam(':id_categoria', $producto['categoria']);
					$sentencia->bindParam(':id_genero', $producto['genero']);
					$sentencia->bindParam(':descuento', $producto['descuento']); 
					
					if ($producto['descuento'] > 0) {
						$sentencia->bindParam(':id_descuento', $num1);	
						$sentencia->bindParam(':oferta', $num1);
					}
					else{
						$sentencia->bindParam(':id_descuento', $num2);	
						$sentencia->bindParam(':oferta', $num2);
					}
			
					$sentencia->bindParam(':costo', $producto['costo']);
					$sentencia->bindParam(':descripcion', $producto['descripcion']);
					$sentencia->bindParam(':caracteristicas', $producto['caracteristicas']);
					$sentencia->bindParam(':img', $producto['img']);
					$sentencia->bindParam(':es_padre', $producto['es_padre']);
					$sentencia->bindParam(':mi_padre', $producto['mi_padre']);
					$sentencia->bindParam(':id_color', $producto['id_color']);
				
          if($sentencia->execute()) return true; 
          else return false; 
		}

		// Resgistrar TALLAS del colores
		public function talla($talla){
			include (APP_PATH . 'config/database.php');
			
			$sql = "INSERT INTO tallas (codigo, nombre, cantidad, id_producto) 
			VALUES (:codigo, :nombre, :cantidad, :id_producto)";

      $sentencia = $pdo->prepare($sql);
      
      $sentencia->bindParam(':codigo', $talla['codigo']);
			$sentencia->bindParam(':nombre', $talla['nombre']);
			$sentencia->bindParam(':cantidad', $talla['cantidad']);
			$sentencia->bindParam(':id_producto', $talla['id_producto']);
			
      if($sentencia->execute()) return true;
      else return false;
		}

    // REGISTRO DE OPERACION DE INVENTARIO

	
		// REGISTRO DE COMPRAS - INVENTARIO

		public function compras($datos)
		{
			include (APP_PATH . 'config/conexion.php');

			$sql = "INSERT INTO compras (numero_factura, numero_control, fecha, fecha_registro, cantidad, costo_unitario, descuento, sub_total, total_iva, alicuota, total_pagado, id_usuario, id_proveedor, id_producto) VALUES (:numero_factura, :numero_control, :fecha, :fecha_registro, :cantidad, :costo_unitario, :descuento, :sub_total, :total_iva, :alicuota, :total_pagado, :id_usuario, :id_proveedor, :id_producto)";
			
					$sentencia = $pdo->prepare($sql);

					$sentencia->bindParam(':numero_factura', $datos['numero_factura']);
					$sentencia->bindParam(':numero_control', $datos['numero_control']);
					$sentencia->bindParam(':fecha', $datos['fecha']);
					$sentencia->bindParam(':fecha_registro', $datos['fecha_registro']);
					$sentencia->bindParam(':cantidad', $datos['cantidad']);
					$sentencia->bindParam(':costo_unitario', $datos['costo_unitario']);
					$sentencia->bindParam(':descuento', $datos['descuento']);
					$sentencia->bindParam(':sub_total', $datos['sub_total']);

					$sentencia->bindParam(':total_iva', $datos['total_iva']);
					$sentencia->bindParam(':alicuota', $datos['alicuota']);
					$sentencia->bindParam(':total_pagado', $datos['total_pagado']);
					$sentencia->bindParam(':id_usuario', $datos['id_usuario']);
					$sentencia->bindParam(':id_proveedor', $datos['id_proveedor']);
					$sentencia->bindParam(':id_producto', $datos['id_producto']);

					if($sentencia->execute())
					{
						return true;
					}
					else
					{	
						return false;
					}
		}
		
    //VENTAS
		public function venta($datos)
		{

			include (APP_PATH . 'config/conexion.php');

			$sql = "INSERT INTO ventas (codigo, cantidad, precio, descuento, total, fecha, id_tipo_precio, id_iva, id_producto, id_cliente, id_vendedor, id_deposito) VALUES (:codigo, :cantidad, :precio, :descuento, :total, :fecha, :id_tipo_precio, :id_iva, :id_producto, :id_cliente, :id_vendedor, :id_deposito)";
			
					$sentencia = $pdo->prepare($sql);

					$sentencia->bindParam(':codigo', $datos['codigo']);
					$sentencia->bindParam(':cantidad', $datos['cantidad']);
					$sentencia->bindParam(':precio', $datos['precio']);
					$sentencia->bindParam(':descuento', $datos['descuento']);
					$sentencia->bindParam(':total', $datos['total']);
					$sentencia->bindParam(':fecha', $datos['fecha']);
					
					
					$sentencia->bindParam(':id_tipo_precio', $datos['id_tipo_precio']);
					$sentencia->bindParam(':id_iva', $datos['id_iva']);
					$sentencia->bindParam(':id_producto', $datos['id_producto']);
					$sentencia->bindParam(':id_cliente', $datos['id_cliente']);
					$sentencia->bindParam(':id_vendedor', $datos['id_vendedor']);
					$sentencia->bindParam(':id_deposito', $datos['id_deposito']);

					if($sentencia->execute())
					{
						return true;
					}
					else
					{	
						return false;
					}
		}

	


	}//cierre de la clase
		
 ?>
