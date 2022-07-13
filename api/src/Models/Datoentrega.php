<?php

namespace App\Models;

class Datoentrega
{
//------------****--------- CONSULTAS --------****--------
    //Retorna la informacion de una entrega por medio del codigo 
  static function show($idUsuaio){
    if($datoEntrega = Ayudador::datos($idUsuaio,'datos_entrega','id_usuario','singular')){
      $respuestaLimpia = Arreglar::paraJsonSingular($datoEntrega);
      return $respuestaLimpia;
    }else return false;
  }


//------------****--------- REGISTROS --------****--------
    static function crear($datoEntrega){
       
        include (APP_PATH . 'config/database.php');
        //Registro de datos de Factura
        $sql =  "INSERT INTO datos_entrega (correo, id_usuario) 
        VALUES(:correo, :id_usuario)";
  
        $sentencia = $pdo->prepare($sql);
  
        $sentencia->bindParam(':correo',  $datoEntrega['email']);
        $sentencia->bindParam(':id_usuario', $datoEntrega['id']);
            
        if($sentencia->execute()) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($datoEntrega){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE datos_entrega SET  nombre = :nombre, 
           departamento = :departamento, provincia = :provincia,
           distrito = :distrito, direccion = :direccion,
           referencia = :referencia, telefono = :telefono
           WHERE id_usuario = :id_usuario ";
           
               $sentencia = $pdo->prepare($sql);
     
               $sentencia->bindParam(':id_usuario', $datoEntrega['idUser']);
               $sentencia->bindParam(':nombre', $datoEntrega['name']);
               $sentencia->bindParam(':departamento', $datoEntrega['departamento']); 
               $sentencia->bindParam(':provincia', $datoEntrega['provincia']); 
               $sentencia->bindParam(':distrito', $datoEntrega['distrito']); 
               $sentencia->bindParam(':direccion', $datoEntrega['direccion']); 
               $sentencia->bindParam(':referencia', $datoEntrega['referencia']); 
               $sentencia->bindParam(':telefono', $datoEntrega['phone']); 
   

        $sentencia->execute(); 
        if($sentencia->rowCount() > 0) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------
  static function eliminar($idDatoEntrega){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM datos_entrega WHERE id_usuario = :id OR correo = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idDatoEntrega);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else return Validar::mensajeSql($sentencia->errorInfo());
      
  }
}
