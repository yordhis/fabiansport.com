<?php

namespace App\Models;

class Factura
{
//------------****--------- CONSULTAS --------****--------
  //CONSULTAR facturas
  static function listar($campos, $numeroDePagina = 1, $ordenarPor = "id DESC"){
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT id FROM facturas";
    $sentencia = $pdo->prepare($sql);
    $limite = 20;
    if ($sentencia->execute()) {

      $totalFilas = $sentencia->rowCount();
      $totalPaginas = $totalFilas/$limite;

      //normalizamos a entero el resultado del total paginas
      if(is_float($totalPaginas)):
        $totalPaginas = strval($totalPaginas);
        $totalPaginas = trim($totalPaginas, '.');
        $totalPaginas = intval($totalPaginas) + 1;
       endif;
     
      $pag_sql="SELECT $campos FROM facturas ORDER BY $ordenarPor LIMIT " . 
      ($limite*$numeroDePagina-$limite).','. $limite;

      $sentencia_pag = $pdo->prepare($pag_sql);
      $sentencia_pag->execute();
      $resultados['facturas'] = $sentencia_pag->fetchAll();
      $resultados['totalPage'] = intval($totalPaginas);
      $resultados['pageActual'] = intval($numeroDePagina);
           
      return $resultados;
    }
  }

  //CONSULTA INDIVIDUAL
  static function show($codigoFactura){
    if($codigoFactura = Ayudador::datos($codigoFactura,'facturas','codigo','*')){
      $respuestaLimpia = Arreglar::paraJsonSingular($codigoFactura);
      return $respuestaLimpia;
    }else return false;
  }
  //CONSULTA FACTURAS DE USUARIO (CLIENTES O MIS COMPRAS)
  static function usuario($idUser){
    if($misCompras = Ayudador::datos($idUser,'facturas','id_cliente','plural','*')){
      foreach ($misCompras as $compra) {
        $respuestaLimpia[]= Arreglar::paraJsonSingular($compra);
      }
      return $respuestaLimpia;
    }else return false;
  }

//------------****--------- REGISTROS --------****--------
  // Resgistrar factura
    static function crear($factura){
        include (APP_PATH . 'config/database.php');
  
        
        $sql =" INSERT INTO facturas (codigo, id_cliente, metodo_pago, img, 
        num_comprobante, fecha_pago, monto_pagado, 
        titular, mayoria_edad, montoUSD)
        VALUES( :codigo, :id_cliente, :metodo_pago, :img, 
        :num_comprobante, :fecha_pago, :monto_pagado,  
        :titular, :mayoria_edad, :montoUSD) ";
  
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':codigo', $factura['code']);
        $sentencia->bindParam(':id_cliente', $factura['idUser']);
        $sentencia->bindParam(':metodo_pago', $factura['typePayment']);
        $sentencia->bindParam(':img', $factura['image']);
        $sentencia->bindParam(':num_comprobante', $factura['refPayment']);
        $sentencia->bindParam(':fecha_pago', $factura['date']);
        $sentencia->bindParam(':monto_pagado', $factura['mount']);
        $sentencia->bindParam(':titular', $factura['titular']);
        $sentencia->bindParam(':mayoria_edad', $factura['isAdult']);
        $sentencia->bindParam(':montoUSD', $factura['mountUSD']);
      
        //Ejecutamos todo
        if($sentencia->execute()) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  static function actualizarEstatusFactura($code, $estatus = 1){
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE facturas SET estatus = :estatus
          WHERE codigo = :codigo OR id = :codigo";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':estatus', $estatus);
					$sentencia->bindParam(':codigo', $code);
          $sentencia->execute();

          $sentencia->execute(); 
          if($sentencia->rowCount() > 0) return true; 
          else return Validar::mensajeSql($sentencia->errorInfo());
  }
  
  static function asignarInfoEntrega($infoEntrega){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE facturas SET 
    numero_envio = :numero_envio,
    tipo_entrega = :tipo_entrega, estatus = 1
    WHERE id = :id ";
    $sentencia = $pdo->prepare($sql); 
   
    $sentencia->bindParam(":numero_envio", $infoEntrega['guia']);        
    $sentencia->bindParam(":tipo_entrega", $infoEntrega['delivery']);              
    $sentencia->bindParam(":id", $infoEntrega['id']); 
    
    $sentencia->execute(); 
    if($sentencia->rowCount() > 0) return true; 
    else return Validar::mensajeSql($sentencia->errorInfo());
  }
  //------------****--------- ELIMINAR --------****--------

  // ELIMINAR FACTURAS
  static function eliminar($idFactura){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM facturas WHERE id = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idFactura);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      } 
  }

}
