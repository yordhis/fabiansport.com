<?php

namespace App\Models;

class Talla
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR TALLAS
  static function listar($idProducto){
    if($tallas = Ayudador::datos($idProducto,'tallas','id_producto','plural',"*")){
      $respuestaLimpia['talla'] = Arreglar::paraJsonPlural($tallas);
      return $respuestaLimpia;
    }else return false;
  }

  //CONSULTA todas las talla del producto
  static function show($talla){
    if($talla = Ayudador::datos($talla,'tallas','id','singular',"*")){
      $respuestaLimpia = Arreglar::paraJsonSingular($talla);
      return $respuestaLimpia;
    }else return false;
  }

  /*Consulta las tallas y suma las cantidades de todas las talla de un producto
  Para darme un Stock TOTAL*/
  static function getCantidad($id, $tabla){
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT cantidad FROM $tabla 
    WHERE id_producto = :identificador OR codigo = :identificador";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':identificador', $id);
    $stock = 0;
    $sentencia->execute();
    if ($tallas = $sentencia->fetchAll()){
      foreach ($tallas as $talla){
        $stock = $stock + $talla['cantidad'];
      }
      //retornar la cantidad total en numero int
      return $stock;
    } 
    else return false;

  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar TALLAS del colores
    static function crear($talla){
      include (APP_PATH . 'config/database.php');
      
      $sql = "INSERT INTO tallas (codigo, nombre, cantidad, id_producto) 
      VALUES (:codigo, :nombre, :cantidad, :id_producto)";

      $sentencia = $pdo->prepare($sql);
      
      $sentencia->bindParam(':codigo', $talla['codigo']);
      $sentencia->bindParam(':nombre', $talla['nombre']);
      $sentencia->bindParam(':cantidad', $talla['cantidad']);
      $sentencia->bindParam(':id_producto', $talla['id_producto']);
      
      if($sentencia->execute()) return true;
      else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($talla){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE tallas SET nombre = :nombre, 
      cantidad = :cantidad, codigo =:codigo
      WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          $sentencia->bindParam(':nombre', $talla['nombre']);
          $sentencia->bindParam(':cantidad', $talla['cantidad']);
          $sentencia->bindParam(':codigo', $talla['codigo']);
          $sentencia->bindParam(':id', $talla['id']);
          $sentencia->execute();
          if($sentencia->rowCount() > 0) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
  }

  static function actualizarCantidad($talla){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE tallas SET  
      cantidad = :cantidad
      WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          $sentencia->bindParam(':cantidad', $talla['quantity']);
          $sentencia->bindParam(':id', $talla['id']);
          $sentencia->execute();
          if($sentencia->rowCount() > 0) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR TALLA
  static function eliminar($idTalla){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM tallas WHERE id = :id OR id_producto = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idTalla);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }
}
