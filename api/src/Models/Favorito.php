<?php

namespace App\Models;

class Favorito
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR favoritos
  static function listar($idUsuario){
    if($favoritos = Ayudador::datos($idUsuario,'favoritos','id_usuario','plural','*')){
    $respuestaLimpia['favoritos'] = Arreglar::paraJsonPlural($favoritos);
    $i=0;
    foreach ($respuestaLimpia['favoritos'] as $img) {
      $respuestaLimpia['favoritos'][$i]['img'] = Arreglar::imgUrl($img['img'], 'favoritos');
      $i++;
    }
    return $respuestaLimpia;
    }
    else return false;
  }

  //CONSULTA IDIVIDUAL
  static function show($favorito){
    if($favorito = Ayudador::datosRelacion($favorito['idUser'],
      $favorito['idProduct'], "id_usuario", "id_producto", 'favoritos','*')){
      $respuestaLimpia = Arreglar::paraJsonSingular($favorito);
      return $respuestaLimpia;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar fa$favoritos del colores
    static function crear($favorito){
      include (APP_PATH . 'config/database.php');
      
      $sql = "INSERT INTO favoritos (id_usuario, id_producto, id_dad, codigo ,costo, nombre, img, descuento) 
      VALUES (:id_usuario, :id_producto, :id_dad, :codigo, :costo, :nombre, :img, :descuento)";

      $sentencia = $pdo->prepare($sql);
    
      $sentencia->bindParam(':id_usuario', $favorito['idUser']);
      $sentencia->bindParam(':id_producto', $favorito['idProduct']);
      $sentencia->bindParam(':id_dad', $favorito['idDad']);
      $sentencia->bindParam(':codigo', $favorito['codigo']);
      $sentencia->bindParam(':costo', $favorito['cost']);
      $sentencia->bindParam(':nombre', $favorito['name']);
      $sentencia->bindParam(':img', $favorito['image']);
      $sentencia->bindParam(':descuento', $favorito['descuento']);
      
      
      if($sentencia->execute()) return true;
      else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($favorito){
    include (APP_PATH . 'config/database.php');

      $sql = "UPDATE favoritos SET  id_usuario = :id_usuario, 
      id_producto = :id_producto, id_dad = :id_dad, 
      codigo = :codigo, costo = :costo, 
      nombre = :nombre, img = :img
      WHERE id = :id ";

      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $favorito['id']);
      $sentencia->bindParam(':id_usuario', $favorito['idUser']);
      $sentencia->bindParam(':id_producto', $favorito['idProduct']);
      $sentencia->bindParam(':id_dad', $favorito['idDad']);
      $sentencia->bindParam(':codigo', $favorito['codigo']);
      $sentencia->bindParam(':costo', $favorito['cost']);
      $sentencia->bindParam(':nombre', $favorito['name']);
      $sentencia->bindParam(':img', $favorito['image']);
  
          $sentencia->execute();
          if($sentencia->rowCount() > 0) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR favorito
  static function eliminar($favorito){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM favoritos WHERE 
      id_usuario = :id_usuario && 
      id_producto = :id_producto";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id_usuario', $favorito['idUser']);
      $sentencia->bindParam(':id_producto', $favorito['idProduct']);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }

}
