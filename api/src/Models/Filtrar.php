<?php

namespace App\Models;

class Filtrar
{
  //Filtro de PRODUCTOS
  static function productos($parametros, $tabla, $campos){
    include (APP_PATH . 'config/database.php');
    //declaramos las variables
    $limite = 45;
    $paginacionSql;
    $condiciones = "";
    $constante = "SELECT $campos FROM $tabla";
    $constantePagina = "SELECT id FROM $tabla";
    
    //validamos si existen paramentos
    $constantePagina .= " WHERE es_padre = 1 AND stock > 0 ";
    $constante .= " WHERE es_padre = 1 AND stock > 0";
    if(count($parametros['params']) > 0){
      //Recorremos los parametrossss
      foreach ($parametros['params'] as $parametro) {
        $condiciones .= " AND id_{$parametro['campo']} = {$parametro['valor']} ";
      }
    }

    //Verifica si hay descuento 
    if ($parametros['descuento'] == 1){
      $condiciones .= " AND descuento > 0 ";
    }

      //relevancia
      if ($parametros['relevancia'] == 1) {
        $condiciones .= " ORDER BY clic DESC ";
      }
      //menor precio
      else if ($parametros['relevancia'] == 2) {
        $condiciones .= " ORDER BY costo ASC ";
      }
      //mayor precio
      else if ($parametros['relevancia'] == 3) {
        $condiciones .= " ORDER BY costo DESC ";
      }
      else {
        $condiciones .= " ORDER BY id DESC ";
      }
  
    //Dibujamos la sentencia SQL 
    $sql = $constantePagina . $condiciones;
   
    //Preparamos la sentencia
    $sentencia = $pdo->prepare($sql);
    //Ejecutamos
      if ($sentencia->execute()) 
      {

        $totalFilas = $sentencia->rowCount();
       
        $totalPaginacion = $totalFilas/$limite;
        //normalizamos a entero el resultado del total paginas
        if(is_float($totalPaginacion)):
         $totalPaginacion = strval($totalPaginacion);
         $totalPaginacion = trim($totalPaginacion, '.');
         $totalPaginacion = intval($totalPaginacion) + 1;
        endif;
     
     
        //Dibujamos la segunda sentencia SQL para la consulta filtrada
        $paginacionSql = $constante . $condiciones . 'LIMIT ' . ($limite*$parametros['pageActual']-$limite).','. $limite;
        
        $sentenciaPaginacion = $pdo->prepare($paginacionSql);
        $sentenciaPaginacion->execute();
        //llenamos nuestro arreglo resultado
        $resultadosPaginacion['codigosPadres'] = $sentenciaPaginacion->fetchAll();
       
        $resultadosPaginacion['totalPage'] = intval($totalPaginacion);
        $resultadosPaginacion['pageActual'] = intval($parametros['pageActual']);
        $respuestaProducto = Arreglar::productos($resultadosPaginacion);
     
        return $respuestaProducto;
        
      } 
      else 
      {
        return false;
      }

  }
//######################################################################//
  //Filtro de PRODUCTOS PARA EL ADMINISTRADOR
  static function productosFiltroAdministrador($parametros, $tabla, $campos){
    include (APP_PATH . 'config/database.php');
    //declaramos las variables
    $limite = 45;
    $paginacionSql;
    $condiciones = "";
    $constante = "SELECT $campos FROM $tabla";
    $constantePagina = "SELECT id FROM $tabla";
    $c = 0;
    

    //validamos si existen paramentos
    if(count($parametros['params']) > 0):
      $constantePagina .= " WHERE";
      $constante .= " WHERE";
    
        //Recorremos los parametrossss
        foreach ($parametros['params'] as $parametro): 
          if ($c == 0): 
            $condiciones .= " id_{$parametro['campo']} = {$parametro['valor']} ";
            $c++;
            continue;
          endif;
          $condiciones .= " AND id_{$parametro['campo']} = {$parametro['valor']} ";
        endforeach;
    endif;


    //Verifica si hay descuento 
    if ($parametros['descuento'] == 1):
      if (count($parametros['params']) > 0):
        $condiciones .= " AND descuento > 0 ";
      else:
        $condiciones .= " WHERE descuento > 0 ";
      endif;
    endif;

    //verificamos si hay parametro de stock
    if ($parametros['stock'] == 1):
      if (count($parametros['params']) > 0):
        $condiciones .= " AND stock = 0 ";
      else:
        $condiciones .= " WHERE stock = 0 ";
      endif;
    endif;

      //relevancia
      if ($parametros['relevancia'] == 1) {
        $condiciones .= " ORDER BY clic DESC ";
      }
      //menor precio
      else if ($parametros['relevancia'] == 2) {
        $condiciones .= " ORDER BY costo ASC ";
      }
      //mayor precio
      else if ($parametros['relevancia'] == 3) {
        $condiciones .= " ORDER BY costo DESC ";
      }
      else {
        $condiciones .= " ORDER BY id DESC ";
      }
  
    //Dibujamos la sentencia SQL 
    $sql = $constantePagina . $condiciones;
   
    //Preparamos la sentencia
    $sentencia = $pdo->prepare($sql);
    //Ejecutamos
      if ($sentencia->execute()){
        //paginacion
        $totalFilas = $sentencia->rowCount();
        $totalPaginacion = $totalFilas/$limite;
        //normalizamos a entero el resultado del total paginas
        if(is_float($totalPaginacion)):
         $totalPaginacion = strval($totalPaginacion);
         $totalPaginacion = trim($totalPaginacion, '.');
         $totalPaginacion = intval($totalPaginacion) + 1;
        endif;
     
        //Dibujamos la segunda sentencia SQL para la consulta filtrada
        $paginacionSql = $constante . $condiciones . 'LIMIT ' . ($limite*$parametros['pageActual']-$limite).','. $limite;

        $sentenciaPaginacion = $pdo->prepare($paginacionSql);
        $sentenciaPaginacion->execute();
        //llenamos nuestro arreglo resultado
        $resultadosPaginacion['productos'] = $sentenciaPaginacion->fetchAll();
       
        $resultadosPaginacion['totalPage'] = intval($totalPaginacion);
        $resultadosPaginacion['pageActual'] = intval($parametros['pageActual']);
      
        //arreglamos los datos
        $respuestaProducto= Arreglar::productosAdmin($resultadosPaginacion);
        return $respuestaProducto;

      } 
      else 
      {
        return false;
      }

  }


}
