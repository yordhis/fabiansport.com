<?php

namespace App\Models;

class Color
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR genero
  static function listar(){
    if($colores = Ayudador::getDatos('colores','*')){
    $respuestaLimpia['colores'] = Arreglar::paraJsonPlural($colores);
    return $respuestaLimpia;
    }
    else return false;
  }

  //CONSULTA IDIVIDUAL
  static function show($idColor){
    if($color = Ayudador::datos($idColor,'colores','id','*')){
      $respuestaLimpia['color'] = Arreglar::paraJsonSingular($color);
      return $respuestaLimpia;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar color 
    static function crear($color){
      include (APP_PATH . 'config/database.php');
      
      $sql = "INSERT INTO colores ( nombre, hexadecimal) 
      VALUES (:nombre, :hexadecimal)";

      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':nombre', $color['nombre']);
      $sentencia->bindParam(':hexadecimal', $color['hexadecimal']);
      
      if($sentencia->execute()) return true;
      else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($color){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE colores SET  nombre = :nombre
      WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          $sentencia->bindParam(':nombre', $color['nombre']);
          $sentencia->bindParam(':id', $color['id']);
          $sentencia->execute();

          if($sentencia->rowCount() > 0) return true; 
          else return false;
  }
    
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR COLOR
  static function eliminar($idColor){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM colores WHERE id = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idColor);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        // return $sentencia->errorInfo();
        return Validar::mensajeSql($sentencia->errorInfo());
        
      } 
  }
}
