<?php

namespace App\Models;

class Datofactura
{
//------------****--------- CONSULTAS --------****--------
    //Retorna la informacion de una factura por medio del codigo 
  static function show($idUsuaio){
    if($datoFactura = Ayudador::datos($idUsuaio,'datos_factura','id_usuario','singular')){
      $respuestaLimpia = Arreglar::paraJsonSingular($datoFactura);
      return $respuestaLimpia;
    }else return false;
  }


//------------****--------- REGISTROS --------****--------
    static function crear($datoFactura){
       
        include (APP_PATH . 'config/database.php');
        //Registro de datos de Factura
        $sql =  "INSERT INTO datos_factura (correo, id_usuario) 
        VALUES(:correo, :id_usuario)";
  
        $sentencia = $pdo->prepare($sql);
  
        $sentencia->bindParam(':correo',  $datoFactura['email']);
        $sentencia->bindParam(':id_usuario', $datoFactura['id']);
            
        if($sentencia->execute()) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($datoFactura){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE datos_factura SET correo = :correo, 
    razon_social = :razon_social, departamento = :departamento, 
    provincia = :provincia, distrito = :distrito, 
    direccion = :direccion, tipo_documento = :tipo_documento, telefono = :telefono, 
    tipo_contribuyente = :tipo_contribuyente, identificacion = :identificacion 
    WHERE id_usuario = :id_usuario ";
    
        $sentencia = $pdo->prepare($sql);

        $sentencia->bindParam(':id_usuario', $datoFactura['idUser']);
        $sentencia->bindParam(':correo', $datoFactura['email']);
        $sentencia->bindParam(':razon_social', $datoFactura['razonSocial']);
        $sentencia->bindParam(':departamento', $datoFactura['departamento']); 
        $sentencia->bindParam(':provincia', $datoFactura['provincia']); 
        $sentencia->bindParam(':distrito', $datoFactura['distrito']); 
        $sentencia->bindParam(':direccion', $datoFactura['direccion']); 
        $sentencia->bindParam(':tipo_documento', $datoFactura['typeDocument']); 
        $sentencia->bindParam(':tipo_contribuyente', $datoFactura['tipoContribuyente']); //tipoContribuyente 
        $sentencia->bindParam(':identificacion', $datoFactura['documentID']);  
        $sentencia->bindParam(':telefono', $datoFactura['phone']); 

        $sentencia->execute(); 
        if($sentencia->rowCount() > 0) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------
  static function eliminar($idDatoFactura){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM datos_factura WHERE id_usuario = :id OR correo = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idDatoFactura);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }
}
