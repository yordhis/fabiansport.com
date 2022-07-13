<?php

namespace App\Models;

class Marca
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR marcas
  static function listar(){
    if($marcas = Ayudador::getDatos('marcas','*')){
    $respuestaLimpia['marcas'] = Arreglar::paraJsonPlural($marcas);
    $i=0;
    foreach ($respuestaLimpia['marcas'] as $img) {
      $respuestaLimpia['marcas'][$i]['img'] = Arreglar::imgUrl($img['img'], 'marcas');
      $i++;
    }
  
    return $respuestaLimpia;
    }
    else return false;
  }

  //CONSULTA IDIVIDUAL
  static function show($marca){
    if($marca = Ayudador::datos($marca,'marcas','id','*')){
      $respuestaLimpia['marca'] = Arreglar::paraJsonSingular($marca);
      return $respuestaLimpia;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar marcas del colores
    static function crear($marca){
      include (APP_PATH . 'config/database.php');
      
      $sql = "INSERT INTO marcas (nombre, img) 
      VALUES (:nombre, :img)";

      $sentencia = $pdo->prepare($sql);
    
      $sentencia->bindParam(':nombre', $marca['nombre']);
      $sentencia->bindParam(':img', $marca['img']);
      
      
      if($sentencia->execute()) return true;
      else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($marca){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE marcas SET  nombre = :nombre, 
      img = :img
      WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          $sentencia->bindParam(':nombre', $marca['nombre']);
          $sentencia->bindParam(':img', $marca['img']);
          $sentencia->bindParam(':id', $marca['id']);
  
          $sentencia->execute();
          if($sentencia->rowCount() > 0) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR TALLA
  static function eliminar($idMarca){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM marcas WHERE id = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idMarca);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }

}
