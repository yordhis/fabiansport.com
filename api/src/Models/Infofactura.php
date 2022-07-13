<?php

namespace App\Models;

class Infofactura
{
//------------****--------- CONSULTAS --------****--------
    //Retorna la informacion de una factura por medio del codigo 
  static function show($codigoFactura){
    if($infoFactura = Ayudador::datos($codigoFactura,'dato_factura','codigo','singular')){
      $respuestaLimpia['infoFactura'] = Arreglar::paraJsonSingular($infoFactura);
      return $respuestaLimpia;
    }else return false;
  }


//------------****--------- REGISTROS --------****--------
    static function crear($infoFactura, $codigoFactura){
        include (APP_PATH . 'config/database.php');
       
        $sql = "INSERT INTO info_factura
        (codigo, correo, razon_social, departamento,
         provincia, distrito, direccion, tipo_documento, 
         tipo_contribuyente, identificacion, telefono) 
         VALUES (:codigo, :correo, :razon_social, :departamento,
         :provincia, :distrito, :direccion, :tipo_documento, 
         :tipo_contribuyente, :identificacion, :telefono)";
        
            $sentencia = $pdo->prepare($sql);

            $sentencia->bindParam(':codigo', $codigoFactura);
            $sentencia->bindParam(':correo', $infoFactura['email']);
            $sentencia->bindParam(':razon_social', $infoFactura['razonSocial']);
            $sentencia->bindParam(':departamento', $infoFactura['departamento']); 
            $sentencia->bindParam(':provincia', $infoFactura['provincia']); 
            $sentencia->bindParam(':distrito', $infoFactura['distrito']); 
            $sentencia->bindParam(':direccion', $infoFactura['direccion']); 
            $sentencia->bindParam(':tipo_documento', $infoFactura['typeDocument']); 
            $sentencia->bindParam(':tipo_contribuyente', $infoFactura['tipoContribuyente']);
            $sentencia->bindParam(':identificacion', $infoFactura['documentID']);
            $sentencia->bindParam(':telefono', $infoFactura['phone']); 
                        
            if($sentencia->execute()) return true; 
            else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizar($infoFactura){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE dato_factura SET correo = :correo, razon_social = :razon_social,
    departamento = :departamento, provincia = :provincia, distrito = :distrito,
    direccion = :direccion, tipo_documento = :tipo_documento, 
    tipo_contribuyente = :tipo_contribuyente, identificacion = :identificacion,
    telefono = :telefono
    WHERE codigo = :codigo ";
    
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':codigo', $infoFactura['code']);
        $sentencia->bindParam(':correo', $infoFactura['email']);
        $sentencia->bindParam(':razon_social', $infoFactura['razonSocial']);
        $sentencia->bindParam(':departamento', $infoFactura['departamento']); 
        $sentencia->bindParam(':provincia', $infoFactura['provincia']); 
        $sentencia->bindParam(':distrito', $infoFactura['distrito']); 
        $sentencia->bindParam(':direccion', $infoFactura['direccion']); 
        $sentencia->bindParam(':tipo_documento', $infoFactura['typeDocument']); 
        $sentencia->bindParam(':tipo_contribuyente', $infoFactura['tipoContribuyente']); 
        $sentencia->bindParam(':identificacion', $infoFactura['documentID']);
        $sentencia->bindParam(':telefono', $infoFactura['phone']); 

        $sentencia->execute(); 
        if($sentencia->rowCount() > 0) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
  }
    
  //------------****--------- ELIMINAR --------****--------
  static function eliminar($codigoFactura){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM info_factura WHERE id = :id OR codigo = :id";
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
