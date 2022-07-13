<?php

namespace App\Models;

class Genero
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR genero
  static function listar(){
    if($generos = Ayudador::getDatos('generos','*')){
    $respuestaLimpia['generos'] = Arreglar::paraJsonPlural($generos);
    return $respuestaLimpia;
    }
    else return false;
  }

  //CONSULTA IDIVIDUAL
  static function show($idGenero){
    if($genero = Ayudador::datos($idGenero,'generos','id','*')){
      $respuestaLimpia['genero'] = Arreglar::paraJsonSingular($genero);
      return $respuestaLimpia;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar genero 
    static function crear($genero){
      include (APP_PATH . 'config/database.php');
      
      $sql = "INSERT INTO generos ( nombre) 
      VALUES (:nombre)";

      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':nombre', $genero['nombre']);
      
      if($sentencia->execute()) return true;
      else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($genero){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE generos SET  nombre = :nombre
      WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          $sentencia->bindParam(':nombre', $genero['nombre']);
          $sentencia->bindParam(':id', $genero['id']);
          $sentencia->execute();

          if($sentencia->rowCount() > 0) return true; 
          else{
            if($sentencia->errorInfo() != null){
              return Validar::mensajeSql($sentencia->errorInfo());
            }else {
              return false;  
            }
          }
  }
    
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR genero
  static function eliminar($idGenero){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM generos WHERE id = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idGenero);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }
}
