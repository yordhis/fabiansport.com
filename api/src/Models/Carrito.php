<?php

namespace App\Models;

class Carrito
{
//------------****--------- CONSULTAS --------****--------

  //CONSULTAR PRODUCTOS DEL CARRITO
  static function show($codigofactura){
    if($carrito = Ayudador::datos($codigofactura,'carritos','codigo','plural')){
      return $carrito;
    }else return false;
  }

  //CONSULTAR UN PRODUCTO DEL CARRITO
  static function traerUnPoductoCarrito($idCarrito){
    if($carrito = Ayudador::datos($idCarrito,'carritos','id','singular')){
      return $carrito;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Agregar al carrito 
    static function crear($carrito, $codigoFactura){
        include (APP_PATH . 'config/database.php');

        $sql =" INSERT INTO carritos
        (codigo, id_producto, id_talla, cantidad, costo, nombre, img, talla, color,
        sexo, categoria, marca, linea) 
        VALUES 
        (:codigo, :id_producto, :id_talla, :cantidad, :costo, :nombre, :img, :talla, :color,
        :sexo, :categoria, :marca, :linea)";
  
        $sentencia = $pdo->prepare($sql);
  
        $sentencia->bindParam(':codigo', $codigoFactura);
        $sentencia->bindParam(':id_producto', $carrito['id']);
        $sentencia->bindParam(':id_talla', $carrito['size']['id']);
        $sentencia->bindParam(':cantidad', $carrito['quantity']);
        $sentencia->bindParam(':costo', $carrito['costo']);
        $sentencia->bindParam(':nombre', $carrito['name']);
        $sentencia->bindParam(':img', $carrito['image']);
        $sentencia->bindParam(':talla', $carrito['size']['name']);
        $sentencia->bindParam(':color', $carrito['color']['name']);
        $sentencia->bindParam(':sexo', $carrito['sex']);
        $sentencia->bindParam(':categoria', $carrito['categoria']);
        $sentencia->bindParam(':marca', $carrito['marca']);
        $sentencia->bindParam(':linea', $carrito['linea']);
        
        //Ejecutamos todo
        if($sentencia->execute()) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($productoCarrito)
  {
    include (APP_PATH . 'config/database.php');
    $sql =  "UPDATE carritos SET 
    id_talla = :id_talla, cantidad = :cantidad, 
    talla =:talla, color = :color
    WHERE id = :idCarrito ";

    $sentencia = $pdo->prepare($sql);
    
    $sentencia->bindParam(':idCarrito', $productoCarrito['idCar']);
    $sentencia->bindParam(':id_talla', $productoCarrito['size']['id']);
    $sentencia->bindParam(':talla', $productoCarrito['size']['name']);
    $sentencia->bindParam(':color', $productoCarrito['color']['name']);
    $sentencia->bindParam(':cantidad', $productoCarrito['quantity']);
    $sentencia->execute();

    $filaAfectadas = $sentencia->rowCount();
    if($filaAfectadas > 0) return true; 
    else{
      return Validar::mensajeSql($sentencia->errorInfo());
    } 
  
  }
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR carrito
  static function eliminarProductoDelCarrito($idCarrito){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM carritos WHERE id = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idCarrito);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        // return $sentencia->errorInfo();
        return Validar::mensajeSql($sentencia->errorInfo());
        
      } 
  }
  // ELIMINAR carrito
  static function eliminar($codigofactura){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM carritos WHERE codigo = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $codigofactura);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        // return $sentencia->errorInfo();
        return Validar::mensajeSql($sentencia->errorInfo());
        
      } 
  }
}
