<?php

namespace App\Models;

class Infoentrega
{
//------------****--------- CONSULTAS --------****--------
    //Retorna la informacion de una factura por medio del codigo 
  static function show($codigoFactura){
    if($infoEntrega = Ayudador::datos($codigoFactura,'info_entrega','codigo','singular')){
      $respuestaLimpia['infoEntrega'] = Arreglar::paraJsonSingular($infoEntrega);
      return $respuestaLimpia;
    }else return false;
  }


//------------****--------- REGISTROS --------****--------
    static function crear($infoEntrega){
      include (APP_PATH . 'config/database.php');

      $sql = "INSERT INTO info_entrega 
      (codigo, nombre, departamento,
       provincia, distrito, direccion,
       referencia, telefono ) 
       VALUES (:codigo, :nombre, :departamento,
       :provincia, :distrito, :direccion,
       :referencia, :telefono)";
      
          $sentencia = $pdo->prepare($sql);

          $sentencia->bindParam(':codigo', $infoEntrega['code']);
          $sentencia->bindParam(':nombre', $infoEntrega['name']);
          $sentencia->bindParam(':departamento', $infoEntrega['departamento']); 
          $sentencia->bindParam(':provincia', $infoEntrega['provincia']); 
          $sentencia->bindParam(':distrito', $infoEntrega['distrito']); 
          $sentencia->bindParam(':direccion', $infoEntrega['direccion']); 
          $sentencia->bindParam(':referencia', $infoEntrega['referencia']); 
          $sentencia->bindParam(':telefono', $infoEntrega['phone']); 
                          
          if($sentencia->execute()) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($infoEntrega){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE info_entrega SET codigo = :codigo, nombre = :nombre, departamento = :departamento,
    provincia = :provincia, distrito = :distrito, direccion = :direccion,
    referencia = :referencia, telefono = :telefono 
    WHERE codigo = :codigo ";
    
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':codigo', $infoEntrega['code']);
        $sentencia->bindParam(':nombre', $infoEntrega['name']);
        $sentencia->bindParam(':departamento', $infoEntrega['departamento']); 
        $sentencia->bindParam(':provincia', $infoEntrega['provincia']); 
        $sentencia->bindParam(':distrito', $infoEntrega['distrito']); 
        $sentencia->bindParam(':direccion', $infoEntrega['direccion']); 
        $sentencia->bindParam(':referencia', $infoEntrega['referencia']); 
        $sentencia->bindParam(':telefono', $infoEntrega['phone']); 

        $sentencia->execute(); 
        if($sentencia->rowCount() > 0) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------
  static function eliminar($codigoFactura){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM info_entrega WHERE id = :id OR codigo = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $codigoFactura);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }
}
