<?php

namespace App\Models;

use App\Models\{Arreglar, Filtrar};

class Producto
{
  // ------------****--------- CONSULTAS ------------****--------- 

  //Devuelve todos los productos padres
  static function listar($tabla, $camposDeLaTabla, $ordenarPor = "id", $numeroPagina = 1)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id FROM $tabla WHERE es_padre = 1 ";
    $sentencia = $pdo->prepare($sql);
    $limiteDePaginas = 45;
    if ($sentencia->execute()) {
      $totalFilas = $sentencia->rowCount();
      $totalPagina = $totalFilas / $limiteDePaginas;

      //normalizamos a entero el resultado del total paginas
      if (is_float($totalPagina)) :
        $totalPagina = strval($totalPagina);
        $totalPagina = trim($totalPagina, '.');
        $totalPagina = intval($totalPagina) + 1;
      endif;

      $paginaSql = "SELECT $camposDeLaTabla FROM productos WHERE es_padre = 1 AND stock > 0 
            ORDER BY id DESC LIMIT " .
        ($limiteDePaginas * $numeroPagina - $limiteDePaginas) . ',' . $limiteDePaginas;

      $sentenciaPaginaPdo = $pdo->prepare($paginaSql);
      $sentenciaPaginaPdo->execute();
      $resultados['codigosPadres'] = $sentenciaPaginaPdo->fetchAll();
      //limpiamos los codigos
      $resultados['codigosPadres'] = Arreglar::paraJsonPlural($resultados['codigosPadres']);
      $resultados['totalPage'] = $totalPagina;
      $resultados['pageActual'] = intval($numeroPagina);
     

      $respuestaProducto = Arreglar::productos($resultados);
      return $respuestaProducto;
    } else {
      return false;
    }
  }


  //Devuelve los datos de un producto
  static function show($idProducto){
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT * FROM productos WHERE id = :id OR codigo = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $idProducto);
    $sentencia->execute();

    $resultado;
    if ($resultado = $sentencia->fetch()) {
      //validar si es padre o no 
      if ($resultado['es_padre'] == 1) {
        $codigos = Ayudador::datos($resultado['codigo'], "productos", "mi_padre","plural", "codigo","id ASC");
        $resultado = Arreglar::producto($codigos);
        return $resultado;
      }else{
        $idPadre = Ayudador::datos($resultado['mi_padre'], "productos", "codigo", "singular", "id");
        $idPadre = Arreglar::paraJsonSingular($idPadre);
        return $idPadre;
      } 
    }
    return false;
  }



  //Devuelve los datos de un producto PADRE O HIJO
  static function getProductoDetalle($id, $tabla, $tipoUsuario = "admin")
  {
    include(APP_PATH . 'config/database.php');

    $sql = "SELECT * FROM $tabla WHERE codigo = :id OR id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();

    $resultado;
    if ($resultado = $sentencia->fetch()) {
      
      $resultado["marca"] = Ayudador::getNombre($resultado["id_marca"], "marcas", "nombre");
      $resultado["linea"] = Ayudador::getNombre($resultado["id_linea"], "lineas", "nombre");
      $resultado["categoria"] = Ayudador::getNombre($resultado["id_categoria"], "categorias", "nombre");
      $resultado["genero"] = Ayudador::getNombre($resultado["id_genero"], "generos", "nombre");

      //normalizamos la respuesta
      $repuesta = Arreglar::productoDetalle($resultado, $tipoUsuario);

      return $repuesta;
    }
    return false;
  }


  //Devuelve todos los productos hijos y padres en una lista
  static function getProductosAdmin($tabla, $campos, $ordenarPor = "id", $paginaActual = 1)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT id FROM $tabla";
    $sentencia = $pdo->prepare($sql);
    $limite = 45;
    if ($sentencia->execute()) {
      $totalFilas = $sentencia->rowCount();
      $totalPaginas = $totalFilas / $limite;

      //normalizamos a entero el resultado del total paginas
      if (is_float($totalPaginas)) {
        $totalPaginas = strval($totalPaginas);
        $totalPaginas = trim($totalPaginas, '.');
        $totalPaginas = intval($totalPaginas) + 1;
      }

      $paginacionSql = "SELECT $campos FROM productos ORDER BY id DESC LIMIT " .
        ($limite * $paginaActual - $limite) . ',' . $limite;

      $sentenciaPaginacionPdo = $pdo->prepare($paginacionSql);
      $sentenciaPaginacionPdo->execute();
      $resultados['productos'] = $sentenciaPaginacionPdo->fetchAll();
      $resultados['totalPage'] = $totalPaginas;
      $resultados['pageActual'] = intval($paginaActual);

      //arreglamos la respuesta
      $respuestaProducto = Arreglar::productosAdmin($resultados);
      return $respuestaProducto;
    } else {
      return false;
    }
  }

  //Devuelve los productos hijos de un padre
  static function getHijos($codePadre)
  {
    $modelos[] = Ayudador::datos($codePadre, "productos", "mi_padre", "plural", "*", "id ASC");
    if (count($modelos) > 0) return $modelos;
    else return false;
  }

  //Devuelve false si es hijo y true si es padre
  static function es_padre($id)
  {
    include (APP_PATH . 'config/database.php');
    $sql = "SELECT id FROM productos WHERE es_padre = 1 AND id = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(":id", $id);
    if ($sentencia->execute()) {
      $esPadre = [$sentencia->fetch()];
      if ($esPadre[0] > 0) return true;
      else return false;
    } else echo "{'mensaje': 'error en la verificacion del padre'}";
  }

  //Devuelve el total del STOCK del producto
  static function totalStock($codigoPadre)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "SELECT stock FROM productos WHERE mi_padre = :codigo ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(":codigo", $codigoPadre);

    if ($sentencia->execute()) {
      $stock = $sentencia->fetchAll();
      $totalStock = 0;
      for ($i = 0; $i < count($stock); $i++) {
        $totalStock = $totalStock + $stock[$i]['stock'];
      }
      return $totalStock;
    } else {
      return Validar::mensajeSql($sentencia->errorInfo());
    }
  }

  //Buscador de productos agil
  static function buscador($dato){
    include (APP_PATH . 'config/database.php');
    //declaramos la variable donde almacenaremos la respuesta
    $productosData = [];
    //creamos un arra de las palabras
    if($arreglos = explode(' ', $dato));
    else $arreglos = [$dato];
    $t=0;
    foreach ($arreglos as $arreglo) {
      //premaramos la busqueda
      $rastreo = "%".$arreglo."%";
      //consultamos
      $sql = "SELECT * FROM productos WHERE codigo LIKE '".$rastreo."' OR nombre LIKE '".$rastreo."'
      ORDER BY id DESC ";
      $sentencia = $pdo->prepare($sql);
      $sentencia->execute();
      $resultados['productos'] = $sentencia->fetchAll();
      
      //arreglamos la respuesta
      $productosData = Arreglar::productosAdmin($resultados);  
      
    }
    return $productosData;
  }


  //------------****--------- REGISTROS DE DATOS DE PRODUCTO --------****--------

  // REGISTRO DE PRODUCTOS - INVENTARIO
  static function crear($producto){
    include (APP_PATH . 'config/database.php');
    $num1 = 1;
    $num2 = 0;

    $sql = "INSERT INTO productos (codigo, nombre, id_marca, id_linea, id_categoria, 
                                      id_genero, id_descuento, descuento, costo, 
                                      descripcion, caracteristicas, img, es_padre, 
                                      mi_padre, id_color, oferta) 
                              VALUES (:codigo, :nombre, :id_marca, :id_linea, :id_categoria, 
                                      :id_genero, :id_descuento, :descuento, :costo, 
                                      :descripcion, :caracteristicas, :img, :es_padre,
                                      :mi_padre, :id_color, :oferta)";

    $sentencia = $pdo->prepare($sql);

    $sentencia->bindParam(':codigo', $producto['codigo']);
    $sentencia->bindParam(':nombre', $producto['nombre']);
    $sentencia->bindParam(':id_marca', $producto['marca']);
    $sentencia->bindParam(':id_linea', $producto['linea']);
    $sentencia->bindParam(':id_categoria', $producto['categoria']);
    $sentencia->bindParam(':id_genero', $producto['genero']);
    $sentencia->bindParam(':descuento', $producto['descuento']);

    if ($producto['descuento'] > 0) {
      $sentencia->bindParam(':id_descuento', $num1);
      $sentencia->bindParam(':oferta', $num1);
    } else {
      $sentencia->bindParam(':id_descuento', $num2);
      $sentencia->bindParam(':oferta', $num2);
    }

    $sentencia->bindParam(':costo', $producto['costo']);
    $sentencia->bindParam(':descripcion', $producto['descripcion']);
    $sentencia->bindParam(':caracteristicas', $producto['caracteristicas']);
    $sentencia->bindParam(':img', $producto['img']);
    $sentencia->bindParam(':es_padre', $producto['esPadre']);
    $sentencia->bindParam(':mi_padre', $producto['miPadre']);
    $sentencia->bindParam(':id_color', $producto['idColor']);

    if ($sentencia->execute()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }

  //------------****--------- ACTUALIZACIONES DE PRODUCTOS -----****---------

  //Actualizacion de Producto PADRE
  static function Actualizar($producto)
  {
    include (APP_PATH . 'config/database.php');
    $codigoActual = Ayudador::getDato($producto['id'], "productos", "codigo", "id");
    $num1 = 1;
    $num2 = 0;

    $sql = "UPDATE productos SET 
    codigo=:codigo,
    nombre=:nombre,
    id_linea=:id_linea,
    id_marca=:id_marca,
    id_categoria=:id_categoria,
    id_genero=:id_genero,
    id_descuento=:id_descuento,
    descuento=:descuento,
    costo=:costo,
    descripcion=:descripcion,
    caracteristicas=:caracteristicas,
    img=:img,
    id_color=:id_color,
    mi_padre=:mi_padre,
    oferta=:oferta
    WHERE id=:id";

    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $producto['id']);
    //BINCULACION DE producto
    $sentencia->bindParam(':codigo', $producto['codigo']);
    $sentencia->bindParam(':nombre', $producto['nombre']);
    $sentencia->bindParam(':id_linea', $producto['linea']);
    $sentencia->bindParam(':id_marca', $producto['marca']);
    $sentencia->bindParam(':id_categoria', $producto['categoria']);
    $sentencia->bindParam(':id_genero', $producto['genero']);
    $sentencia->bindParam(':descuento', $producto['descuento']);
    
    if ($producto['descuento'] > 0) {
      $sentencia->bindParam(':id_descuento', $num1);
      $sentencia->bindParam(':oferta', $num1);
    } else {
      $sentencia->bindParam(':id_descuento', $num2);
      $sentencia->bindParam(':oferta', $num2);
    }
    
    $sentencia->bindParam(':costo', $producto['costo']);
    $sentencia->bindParam(':descripcion', $producto['descripcion']);
    $sentencia->bindParam(':caracteristicas', $producto['caracteristicas']);
    $sentencia->bindParam(':img', $producto['img']);
    $sentencia->bindParam(':id_color', $producto['id_color']);
    $sentencia->bindParam(':mi_padre', $producto['mi_padre']);
    // CIERRE DE VINCULACION

    if ($sentencia->execute()) {
      if ($sentenciaHeredera = Producto::herenciaAdn($producto, $codigoActual)) 
      {
        return true;
      } else {
        return $sentenciaHeredera;
      }
    } else {
      return Validar::mensajeSql($sentencia->errorInfo());
    }
  }
  //Esta funcion detecta si el padre se queda sin stock 
  //y procede a heredar a un hijo sin eliminar al padra
  //y el padre pasa a ser hijo
  static function stockCero($idProducto){
      //detectamos si el producto es padre
      
      $esPadre = Producto::es_padre($idProducto);
      if($esPadre == true) {
        //traemos toda la informacion del producto
        $producto = Ayudador::datos($idProducto,"productos","id","singular", "*");
        //verifica el stock
        if($producto['stock'] == 0){
            //Llamamos a los hijos 
            $hijos = Producto::getHijos($producto['codigo']);
            //Estatus de la herencia
            $heredo = false;
            //Heredamos al hijo mayor
            if(!empty($hijos)){
              foreach ($hijos[0] as $hijo){
                if($hijo['id'] != $idProducto && $heredo == false && $hijo['stock'] > 0){
                  //otorgamos la primojenitura 
                  $resulHeredero = Producto::heredero($hijo['codigo'], $producto['codigo']);
                  if($resulHeredero != true) return Validar::respuestasHttp($resulHeredero . " No recibio la herencia", 409);
                  //CAMBIO DE ROL
                  $resulNuevoPadre = Producto::nuevoPadre($hijo['id']);
                  $resulNuevoHijo = Producto::nuevoHijo($producto['id']);
                  if($resulNuevoPadre === true && $resulNuevoHijo === true){
                    $heredo = true;
                  }else{ 
                    return Validar::respuestasHttp($resulHeredero . " No recibio el codigo padre", 409);
                  }
                }
                elseif ($heredo === true) {
                  return true;
                }
              }
              if ($heredo === true){
                return true;
              }else {
                return false;
              }
            }else return false;   
          }else return false;
      }else return false;
  }

  // Actualizar heredero (PADRE) convertimos un hijo a padre
  static function heredero($codigoHijo, $codigoPadre){
   
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE productos SET mi_padre = :codigoHijo
    WHERE mi_padre = :codigoPadre";

    $sentencia = $pdo->prepare($sql);

    $sentencia->bindParam(':codigoPadre', $codigoPadre);
    $sentencia->bindParam(':codigoHijo', $codigoHijo);
    $sentencia->execute();
    if ($sentencia->rowCount()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }

  //Asignar el nuevo padre
  static function nuevoHijo($idPadre){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE productos SET es_padre = 0
    WHERE id = :idPadre";

    $sentencia = $pdo->prepare($sql);

    $sentencia->bindParam(':idPadre', $idPadre);
    $sentencia->execute();
    if ($sentencia->rowCount()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }
  //Asignar el nuevo padre
  static function nuevoPadre($idHijo){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE productos SET es_padre = 1
    WHERE id = :idHijo";

    $sentencia = $pdo->prepare($sql);

    $sentencia->bindParam(':idHijo', $idHijo);
    $sentencia->execute();
    if ($sentencia->rowCount()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }
  
  //Actualizacion de Producto PADRE y heredar los nuevos atributos a los hijos 
  static function herenciaAdn($datos, $codigoActual)
  {
    include(APP_PATH . 'config/database.php');
    $num1 = 1;
    $num2 = 0;
    $sql = "UPDATE productos SET  nombre = :nombre, 
      id_linea = :id_linea, id_marca = :id_marca, id_categoria = :id_categoria,
      id_genero = :id_genero, descuento = :descuento, costo = :costo, 
      descripcion = :descripcion,caracteristicas = :caracteristicas, 
      id_descuento = :id_descuento, oferta = :oferta, mi_padre = :mi_padre
      WHERE mi_padre = :codigoActual ";

    $sentencia = $pdo->prepare($sql);
    //BINCULACION DE DATOS
    $sentencia->bindParam(':codigoActual', $codigoActual);
    $sentencia->bindParam(':mi_padre', $datos['codigo']);
    $sentencia->bindParam(':nombre', $datos['nombre']);
    $sentencia->bindParam(':id_linea', $datos['linea']);
    $sentencia->bindParam(':id_marca', $datos['marca']);
    $sentencia->bindParam(':id_categoria', $datos['categoria']);
    $sentencia->bindParam(':id_genero', $datos['genero']);
    $sentencia->bindParam(':descuento', $datos['descuento']);

    if ($datos['descuento'] > 0) {
      $sentencia->bindParam(':id_descuento', $num1);
      $sentencia->bindParam(':oferta', $num1);
    } else {
      $sentencia->bindParam(':id_descuento', $num2);
      $sentencia->bindParam(':oferta', $num2);
    }

    $sentencia->bindParam(':costo', $datos['costo']);
    $sentencia->bindParam(':descripcion', $datos['descripcion']);
    $sentencia->bindParam(':caracteristicas', $datos['caracteristicas']);
    // CIERRE DE VINCULACION
    if ($sentencia->execute()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }

  //Actualizando el stock de los productos
  static function actualizarStock($id)
  {
    //
    $cantidad = Talla::getCantidad($id, "tallas");

    include(APP_PATH . 'config/database.php');

    $sql = "UPDATE productos SET stock = '" . $cantidad . "'
        WHERE id ='" . $id . "' OR  codigo ='" . $id . "' ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute();
    if ($sentencia->rowCount()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }

  //Actualizacion de Producto HIJO esto es cuando 
  //se actualiza el hijo directamente
  static function actualizarProductoHijo($datos)
  {
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE productos SET 
    img = :img, 
    id_color = :id_color, 
    descuento=:descuento,
    costo=:costo,
    codigo=:codigo
    WHERE id = :id";

    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $datos['id']);
    $sentencia->bindParam(':img', $datos['img']);
    $sentencia->bindParam(':id_color', $datos['id_color']);
    $sentencia->bindParam(':descuento', $datos['descuento']);
    $sentencia->bindParam(':costo', $datos['costo']);
    $sentencia->bindParam(':codigo', $datos['codigo']);

    if ($sentencia->execute()) return true;
    else return Validar::mensajeSql($sentencia->errorInfo());
  }

  //Actualizar descuento
  static function descuento($datos){
    include (APP_PATH . 'config/database.php');

    //Actualizar DESCUENTO 
    $sql =  "UPDATE productos SET descuento = :descuento
              WHERE id = :id ";

    $sentencia = $pdo->prepare($sql);

    $sentencia->bindParam(':id', $datos['id']);
    $sentencia->bindParam(':descuento', $datos['descuento']);
    $sentencia->execute();
    if ($sentencia->rowCount()) return true;
    else return false;
  }
  //Actualizar los clic del producto
  static function clic($clic, $id){
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE productos SET clic = :clic
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':clic', $clic);
					$sentencia->bindParam(':id', $id);
          $sentencia->execute();
					if($sentencia->rowCount()):return true; 
          else:return false; endif;
				
  }

  // ELIMINAR PRODUCTO --------------------------------------------------
  static function eliminar($idProducto){

    include (APP_PATH . 'config/database.php');
    $sql = "DELETE FROM productos WHERE id = :id OR codigo = :id";
    $sentencia = $pdo->prepare($sql);
    $sentencia->bindParam(':id', $idProducto);
    $sentencia->execute();
    $filaAfectadas = $sentencia->rowCount();
    if ($filaAfectadas > 0) return true;
    else {
      return Validar::mensajeSql($sentencia->errorInfo());
    }
  }
}//cierre de la clase
