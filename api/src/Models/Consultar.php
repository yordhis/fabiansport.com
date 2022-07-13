<?php
namespace App\Models;

// use App\Models\Metodo;
// use App\Models\Productos;

class Consultar
{
 
  //Retornar datos de tabla por nombre de la tabla
  static function getDatos($tabla, $campo, $ordenarPor = "id"){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campo FROM $tabla ORDER BY $ordenarPor ASC";
    $sentencia = $pdo->prepare($sql);
    if($sentencia->execute()): 
      $resultados = $sentencia->fetchAll(); return $resultados; 
    else: return false; endif;
  }

  static function totalStock($codigoPadre){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT stock FROM productos WHERE mi_padre = :codigo ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(":codigo", $codigoPadre);
    if($sentencia->execute()):
      $stock = $sentencia->fetchAll();
      $totalStock = 0;
      for ($i=0; $i < count($stock); $i++) { 
        $totalStock = $totalStock + $stock[$i]['stock'];
      }return $totalStock;
    else: echo '{"mensaje":"Error en totalizar el stock"}'; endif;
  }

  static function facturas($tabla, $campos, $ordenarPor = "id", $num_page = 1)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id FROM $tabla";
    $sentencia = $pdo->prepare($sql);
    $limite = 4;
    if ($sentencia->execute()) {

      $total_filas = $sentencia->rowCount();
      $total_page = $total_filas/$limite;

      //normalizamos a entero el resultado del total paginas
      if(is_float($total_page)):
        $total_page = strval($total_page);
        $total_page = trim($total_page, '.');
        $total_page = intval($total_page) + 1;
       endif;
     
      $pag_sql="SELECT $campos FROM $tabla ORDER BY id DESC LIMIT " . 
      ($limite*$num_page-$limite).','. $limite;

      $sentencia_pag = $pdo->prepare($pag_sql);
      $sentencia_pag->execute();
      $resultados['facturas'] = $sentencia_pag->fetchAll();
      $resultados['total_page'] = intval($total_page);
      $resultados['page_actual'] = intval($num_page);
           
      return $resultados;

    } else {
      return false;
    }

  }
  // productos admin **
  static function getProductosAdmin($tabla, $campos, $ordenarPor = "id", $num_page = 1)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id FROM $tabla";
    $sentencia = $pdo->prepare($sql);
    $limite = 45;
    if ($sentencia->execute()) {
      $total_filas = $sentencia->rowCount();
      $total_page = $total_filas/$limite;

      //normalizamos a entero el resultado del total paginas
      if(is_float($total_page)):
        $total_page = strval($total_page);
        $total_page = trim($total_page, '.');
        $total_page = intval($total_page) + 1;
       endif;
     
      $pag_sql="SELECT $campos FROM productos ORDER BY id DESC LIMIT " . 
      ($limite*$num_page-$limite).','. $limite;

      $sentencia_pag = $pdo->prepare($pag_sql);
      $sentencia_pag->execute();
      $resultados['productos'] = $sentencia_pag->fetchAll();
      $resultados['total_page'] = $total_page;
      $resultados['page_actual'] = intval($num_page);
            
      return $resultados;

    } else {
      return false;
    }

  }
  // productos ***
  static function getProductos($tabla, $campos, $ordenarPor = "id", $num_page = 1)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id FROM $tabla WHERE es_padre = 1 ";
    $sentencia = $pdo->prepare($sql);
    $limite = 45;
    if ($sentencia->execute()) {
      $total_filas = $sentencia->rowCount();
      $total_page = $total_filas/$limite;

      //normalizamos a entero el resultado del total paginas
      if(is_float($total_page)):
        $total_page = strval($total_page);
        $total_page = trim($total_page, '.');
        $total_page = intval($total_page) + 1;
       endif;
     
      $pag_sql="SELECT $campos FROM productos WHERE es_padre = 1 ORDER BY id DESC LIMIT " . 
      ($limite*$num_page-$limite).','. $limite;

      $sentencia_pag = $pdo->prepare($pag_sql);
      $sentencia_pag->execute();
      $resultados['productos'] = $sentencia_pag->fetchAll();
      $resultados['total_page'] = $total_page;
      $resultados['page_actual'] = intval($num_page);
            
      return $resultados;

    } else {
      return false;
    }

  }

  //Devuelve los datos indexados de un articulo, producto o usuario ***
  static function getProductoCaracteristicasGeneralesPadre($id, $tabla)
  {
    include (APP_PATH . 'config/database.php');
 
    $sql = "SELECT * FROM $tabla WHERE codigo = :id OR id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();

    $resultado;
      if ($resultado = $sentencia->fetch()) 
      {
        
        $resultado["marca"] = Consultar::getNombre($resultado["id_marca"], "marcas", "nombre");
        $resultado["categoria"] = Consultar::getNombre($resultado["id_categoria"], "categorias", "nombre");
        $resultado["genero"] = Consultar::getNombre($resultado["id_genero"], "generos", "nombre");
        
        return $resultado;
      } 
      return false;

  }
  //Devuelve los datos indexados de un articulo, producto o usuario ***
  static function getProducto($id, $tabla)
  {
    include (APP_PATH . 'config/database.php');
 
    $sql = "SELECT * FROM $tabla WHERE id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();

    $resultado;
      if ($resultado = $sentencia->fetch()) 
      {
        
        $resultado["marca"] = Consultar::getNombre($resultado["id_marca"], "marcas", "nombre");
        $resultado["categoria"] = Consultar::getNombre($resultado["id_categoria"], "categorias", "nombre");
        $resultado["genero"] = Consultar::getNombre($resultado["id_genero"], "generos", "nombre");
        $resultado["modelos"] = Consultar::getHijos($resultado["codigo"]);
        
        return $resultado;
      } 
      return false;

  }

  // Retorna el color indexado en los modelos
  static function getNombre($id, $tabla, $campos)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT $campos FROM $tabla WHERE id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);

    if ($sentencia->execute()) {
      $resultado = $sentencia->fetch();
      return $resultado[0];
    } else {
      return false;
    }

  }

  // Retorna un dato de una fila en la tabla espesificada 
  static function getDato($id, $tabla, $campos, $comparar)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT $campos FROM $tabla WHERE $comparar = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);

    if ($sentencia->execute()) {
      $resultado = $sentencia->fetch();
      
      return $resultado[0];
    } else {
      return false;
    }

  }
        // esta funcion nos retorna true si el poducto es padre
        // nos retorna false si es hijo *
        static function es_padre($id)
        {
            include (APP_PATH . 'config/database.php');
            $sql = "SELECT id FROM productos WHERE es_padre = 1 AND id = :id";
            $sentencia = $pdo->prepare($sql);
            $sentencia->bindParam(":id", $id);
            if ($sentencia->execute()):
                $es = ($sentencia->fetch());
                if($es[0] > 0): return true; 
                else: return false; endif;
            else: echo "{'mensaje': 'error en la verificacion del padre'}";endif;

        }
  // Retorna las marcas registradas en la DB
  static function getMarcas($tabla)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT id, nombre, imagen FROM $tabla";
    $sentencia = $pdo->prepare($sql);

    if ($sentencia->execute()) {
      $resultado = $sentencia->fetchAll();
      return $resultado;
    } else {
      return false;
    }

  }
  
  //Consulta las tallas y suma las cantidades de todas las talla de un producto
  //Para darme un Stock
  static function getTallaCantidad($id, $tabla)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT cantidad FROM $tabla WHERE id_producto = :id_producto OR codigo = :id_producto";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id_producto', $id);
    $stock = 0;
    $sentencia->execute();
    if ($tallas = $sentencia->fetchAll()) 
    {
      foreach ($tallas as $talla) 
      {
        $stock = $stock + $talla['cantidad'];
      }
      return $stock;
    } 
    else 
    {
      return false;
    }

  }
  
  // Esta funcion me devuelve un campo, segun el id 
  static function fila($id, $comparar, $campo, $tabla)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campo FROM $tabla WHERE $comparar = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();
    if ($resultado = $sentencia->fetch()) {
      return $resultado;
    } else {
      return false;
    }

  }

  //documentacion del metodo datos()
  /**
   *  @var DATOS metodo de consulta
   *  Me devuelve todos los datos de una tabla o fila
   *  
   *  @var dato es un identificador @var id o @var codigo
   *  @var tabla es la tabla que se va a consultar en la dataBase
   *  @var campo esta variable es el campo que quiero comparar de la dataBase
   *  @var return Esta me permite modificar el retorno de datos usando  
   *  --------------->@var singular o @var plural 
   *  
   */

  static function datos($dato, $tabla, $comparar, $return, $campo_tabla = "*", $ordenar = "id DESC")
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT $campo_tabla FROM $tabla WHERE $comparar = :campo ORDER BY $ordenar ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':campo', $dato);

    if ($sentencia->execute()):
      if ($return == "plural"): $return = "fetchAll"; else: $return = "fetch"; endif;
        $resultado = $sentencia->$return();
        return $resultado;
    else:
      return false;
    endif;

  }

  // Me devuelve todos los datos de una fila
  static function datosRelacion($dato_1, $dato_2, $campo_1, $campo_2, $tabla, $campo_tabla = "*" , $return)
  {
    include(APP_PATH . 'config/database.php');

    $limite = 3;

    $sql = "SELECT $campo_tabla FROM $tabla WHERE $campo_1 = :campo_1 AND $campo_2 = :campo_2";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':campo_1', $dato_1);
    $sentencia->bindParam(':campo_2', $dato_2);

    if ($sentencia->execute()) {
      if ($return == "plural") {
        $return = "fetchAll";
      } else {
        $return = "fetch";
      }

      $resultado = $sentencia->$return();
      return $resultado;
    } else {
      return false;
    }

  }
    
  //Filtro de filas
  static function filtrar($parametros, $tabla, $campos)
  {
    include (APP_PATH . 'config/database.php');
    //declaramos las variables del ambientess
    $limite = 45;
    $pag_sql;
    $condiciones = "";
    $constante = "SELECT $campos FROM $tabla";
    $constante_pag = "SELECT id FROM $tabla";
    $c = 0;
    
    //validamos si existen paramentos
    if(count($parametros['params']) > 0):
      $constante_pag .= " WHERE es_padre = 1 AND";
      $constante .= " WHERE es_padre = 1 AND";
    
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
    $sql = $constante_pag . $condiciones;
   
    //Preparamos la sentencia
    $sentencia = $pdo->prepare($sql);
    //Ejecutamos
      if ($sentencia->execute()) 
      {
        //paginacion
        $total_filas = $sentencia->rowCount();
        $total_page = $total_filas/$limite;
        //normalizamos a entero el resultado del total paginas
        if(is_float($total_page)):
         $total_page = strval($total_page);
         $total_page = trim($total_page, '.');
         $total_page = intval($total_page) + 1;
        endif;
     
        //Dibujamos la segunda sentencia SQL para la consulta filtrada
        $pag_sql = $constante . $condiciones . 'LIMIT ' . ($limite*$parametros['num_page']-$limite).','. $limite;

        $sentencia_pag = $pdo->prepare($pag_sql);
        $sentencia_pag->execute();
        //llenamos nuestro arreglo resultado
        $resultados_pag['productos'] = $sentencia_pag->fetchAll();
       
        $resultados_pag['total_page'] = intval($total_page);
        $resultados_pag['page_actual'] = intval($parametros['num_page']);
       
        //Asignamos los modelos
        // $count = 0;
        // foreach ($resultados_pag['productos'] as $resultado):
        //   if($resultados_pag['productos'][$count]['colores'] = Consultar::getProductoColor($resultado['id'])): 
        //   $count++;
        //   else: echo "fallo el retorno de modelos"; return false; endif;
        // endforeach;
      
        //Devolvemos todos los resultados
        return $resultados_pag;
      } 
      else 
      {
        return false;
      }

  }
  //Retorna los favoritos
  static function getFavoriotos($id)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id_usuario, id_producto FROM favoritos WHERE id_usuario = :id_usuario";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id_usuario', $id);

    if ($sentencia->execute()) {
   
      $resultados = $sentencia->fetchAll();
      if($resultados == null): return "No hay favoritos"; else: return $resultados; endif; 
    } 
    else 
    {
      return false;
    }

  }

  static function buscador($dato, $tabla)
  {
    include (APP_PATH . 'config/database.php');

    $repuestas;
    $productosData = [];
    $arreglos = explode(' ', $dato);
    $t=0;
    foreach ($arreglos as $arreglo) {
      
      $rastreo = "%".$arreglo."%";
      
      $sql = "SELECT id, codigo, nombre, descuento, costo, stock FROM productos WHERE codigo LIKE '".$rastreo."' OR nombre LIKE '".$rastreo."'
      ORDER BY id DESC ";
      
      $sentencia = $pdo->prepare($sql);
      $sentencia->execute();
      $resultados = $sentencia->fetchAll();
      $imagen;
      foreach ($resultados as $producto):
        $modelo = Consultar::datos($producto['codigo'],"producto_color", "codigo", "plural", "id, img");
        $img = explode(",",$modelo[0]['img']);
        $productosData['productos'][$t] = [
          "id" => intval($producto['id']) ?? '',
          "codigo" => $producto['codigo'] ?? '',
          "name" => $producto['nombre'] ?? '',
          "descuento" => intval($producto['descuento']) ?? '',
          "costo" => intval($producto['costo']) ?? '',
          "colores" => count($modelo) ?? '',
          "image" =>   PUBLIC_PATH ."/img/". $img[0],
          "stock" => intval($producto['stock'])
        ];
       
        $t++;
      endforeach;
    }
    
    return $productosData;
    

  }
  // *
  static function getHijos($codePadre)
  {
    include (APP_PATH . 'config/database.php');

    $cantidadDeModelos = Consultar::datos(
      $codePadre, "productos", "mi_padre", "plural"
    );

    return $cantidadDeModelos;
  }
} //Cierre de la clase Consultar

?>
