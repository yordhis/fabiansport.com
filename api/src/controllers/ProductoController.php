<?php

namespace App\Controllers;

//modelos a usar
use App\Models\{Arreglar, Ayudador, Producto, Filtrar};
use App\Models\{Img, Talla, Validar, Formulario};

//

class ProductoController
{
  private $producto;
  //Recibimos los JSON
  public function __construct(){
    $this->producto = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }
    //muestra todos los productos padres
    public function index(){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        //url 
        $p = $_GET['page'] ?? 1;
        //Consulta de la lista de producto
        $this->producto = Producto::listar('productos', "codigo", 'id', $p);
        
        //validamos si hay resultado
        if(empty($this->producto['productos'])){
          return Validar::respuestasHttp("No hay resultados", 200);
        }else{
          Validar::respuestasHttp(null,200);
          return json_encode($this->producto);
        } 
    }

    public function indexFiltro(){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
      
      $filtro = $this->producto ?? null;
      
      if (!empty($filtro)) {
        //Ejecutamos el filtro de los productos
          $resultados = Filtrar::productos($filtro, "productos", "codigo");
        
          if(empty($resultados['productos'])){
            Validar::respuestasHttp(null, 200);
            return  json_encode($resultados);
          }else{
            //Arreglamos la respuesta producto
            Validar::respuestasHttp(null, 200);
            return json_encode($resultados);
          }
      }
    }

    public function show($idProducto){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

      if (!empty($idProducto)){
        if ($repuesta = Producto::show($idProducto, "productos")){
            http_response_code(200);
            return json_encode($repuesta);
        } 
        else{
          
            return Validar::respuestasHttp("No hay resultados", 409);
        }
      } 
      else{
        return Validar::respuestasHttp("No hay parametros de busqueda", 409);
      }

    }

    public function productoDetalle($param){ 
      if (isset($param)) 
      {
        if ($repuesta = Producto::getProductoDetalle($param, "productos")) 
        {
            // //normalizamos la respuesta
            // $repuesta = Arreglar::productoDetalle($productoData);
            //retornamos la respuesta
            http_response_code(200);
            echo json_encode($repuesta);
        } 
          else 
          {
            $respuestaCode = ["mensaje" => "No hay resultados"];
            http_response_code(200);
            echo json_encode($respuestaCode);
          }
      } 
      else 
      {
        $respuestaCode = ["mensaje" => "No hay parametros de busqueda"];
        http_response_code(409);
        echo json_encode($respuestaCode);
      }
  
    }

    public function indexAdmin(){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

        $p = $_GET['page'] ?? 1;
      //consulta de la lista de producto
        $this->producto = Producto::getProductosAdmin('productos', "*", 'id', $p);

        //validamos si hay resultado
        if(empty($this->producto['productos'])){
          Validar::respuestasHttp(null,200);
          return json_encode($this->producto);
        }else{
          Validar::respuestasHttp(null,200);
          return json_encode($this->producto);
        } 
    }

    public function indexAdminFiltro(){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
      //recivimos el filtro
      $filtro = $this->producto;
        //Ejecutamos el filtro de los productos
          $resultados = Filtrar::productosFiltroAdministrador($filtro, "productos", "*");
          http_response_code(200);
          return json_encode($resultados);
    }

    public function totalStock($codigoPadre){
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

      $totalStock = ["totalStock" => Producto::totalStock($codigoPadre)];
        //retornamos la lista de clientes
        http_response_code(200);
        echo json_encode($totalStock);
        return;
    }

    public function buscador()
    {
      //validamos el metodo de envio (se espera GET)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
      //dato recibido
      $productoABuscar = $this->producto['value'];
      //validamos los datos de busqueda
      if($mensaje = Formulario::producto(["value"=>$productoABuscar]))return $mensaje;
        $resultados = Producto::buscador($productoABuscar);  
        if(!$resultados){
            return Validar::respuestasHttp("No hay resultados", 200);
        } else{
            http_response_code(200);
            return json_encode($resultados);
        }    
 
    }


//------------****--------- REGISTROS DE DATOS DE PRODUCTO --------****--------

  public function crear(){
    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], 
    "POST")) return $mensaje;
    
    //datos recibidos
    $producto = $this->producto;
    //emulando el envio de las imagenes
    // $_FILES['name']=[$_FILES[0]['name'],$_FILES[1]['name'],$_FILES[2]['name']];
    // $_FILES['tmp_name']=[$_FILES[0]['tmp_name'],  $_FILES[1]['tmp_name'], $_FILES[2]['tmp_name']];
    
    
    //variables array
    $arregloProducto = [];
    $arregloTallas = [];
    $mensajesError = [];
    //DIRECTORIO
    $diectorio = "productos";
    //Normalizar imagenes para el registro
    if(!empty($_FILES[0]['name'])){
      $imagenLista = Img::mover($_FILES[0], $diectorio, $producto['codigo']);
    }else {
      echo Validar::respuestasHttp('No hay imagenes para almacenar', null);
      $imagenLista = 'Sin imagenes';
    }

   
    //arreglo para reistrar
    $arregloProducto = [
        "codigo" => $producto['codigo'],
        "nombre" => $producto['name'],
        "linea" => intval($producto['filtro']['linea']['id']),
        "categoria" => intval($producto['filtro']['categoria']['id']),
        "genero" => intval($producto['filtro']['sex']['id']),
        "marca" => intval($producto['filtro']['marca']['id']),
        "descuento" => intval($producto['descuento']),
        "costo" => floatval($producto['costo']),
        "descripcion" => $producto['descripcion'],
        "caracteristicas" => $producto['caracteristicas'],
        "img" => $imagenLista,
        "idColor" => intval($producto['id_color']),
        "esPadre" => intval($producto['es_padre']),
        "miPadre" => $producto['mi_padre'],
        "accion" => "registrar"
    ];
    
    // VALIDACION DE TODOS LOS DATOS RECIVIDOS 
    if($mensaje = Formulario::producto($arregloProducto)){ 
      Img::eliminar($arregloProducto['img'], 'productos/');
      return $mensaje;
    }

    // ejecutamos crear el producto
    $resultadoDeRegistro = Producto::crear($arregloProducto);

    //validamos la ejecucion 
    if ($resultadoDeRegistro == true){
      //obtenemos el id del producto registrado para registrar las tallas
      $idProducto = Ayudador::fila($arregloProducto['codigo'], "codigo", "id", "productos");
      // print_r($idProducto); return;
    }else{
      array_push($mensajesError,"$resultadoDeRegistro (Fallo el registro)");
    }
    //Talla 
    foreach ($producto['sizes'] as $size) {
      $arregloTalla =
        [
          "codigo" => $arregloProducto['codigo'],
          "nombre" => $size['name'],
          "cantidad" => $size['quantity'],
          "id_producto" => $idProducto[0]
        ];
      if (!Talla::crear($arregloTalla)){
        //capturando Errores
        array_push($mensajesError,"{'mensaje': 'No se registro la talla'}");
      }
    }

    // Actualizamos la existencia del producto registrado
    if(!Producto::actualizarStock($producto['codigo'])) array_push($mensajesError,
    "{'mensaje': 'No se actualizo el Stock'}");

    // Validamos si el registro se ejecuto sin errores
    if (count($mensajesError) > 0){
      //convertimos el array en cadena de texto
      $mensajesError = implode(', ', $mensajesError);
      //destruimos el registro
      if(Ayudador::destruirRegistro($arregloProducto['codigo'], 
      $arregloProducto['img'])){
        return Validar::respuestasHttp($mensajesError . 'Fallo el registros', 409);
      }
    }else{
      return Validar::respuestasHttp('Registo exitoso', 201);
    }
  }
  //Cierres de los registros

	// ACTUALIZAR PRODUCTO
  public function actualizar(){
    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], 
    "POST")) return $mensaje;
    // Datos recibidos
    $producto = $this->producto;
    //variables array
    $arregloProducto = [];
    $arregloTallas = [];
    $mensajesError = [];
    //DIRECTORIO
    $directorio = "productos";

    //obtenemos las imagenes actuales
    $producto['imgActuales'] = Img::show($producto['id'], 'productos');
    $producto['imgActuales'] = implode(',', $producto['imgActuales']['imagenDB']);

    //Normalizar imagenes para el registro
    if(!empty($_FILES[0]['name'])){
      $imagenLista = Img::mover($_FILES[0], $directorio, $producto['codigo'], $producto['imgActuales']);
    }else {
      Validar::respuestasHttp('No hay imagenes para almacenar', null);
      $imagenLista = $producto['imgActuales'];
    }

    // print_r($imagenLista);
    //Arreglo PRODUCTO
    $arregloProducto = [
      "id" => $producto['id'],
      "codigo" => $producto['codigo'],
      "nombre" => $producto['name'],
      "genero" => $producto['filtro']['sex']['id'],
      "categoria" => $producto['filtro']['categoria']['id'],
      "marca" => $producto['filtro']['marca']['id'],
      "linea" => $producto['filtro']['linea']['id'],
      "descuento" => $producto['descuento'],
      "costo" => $producto['costo'],
      "descripcion" => $producto['descripcion'],
      "caracteristicas" => $producto['caracteristicas'],
      "img" =>  $imagenLista,
      "id_color" => $producto['id_color'],
      "mi_padre" => $producto['mi_padre'],
      "es_padre" => $producto['es_padre'],
      "accion" => "actualizar"
    ];
    
    // VALIDACION DE TODOS LOS DATOS RECIBIDOS 
    if($mensaje = Formulario::producto($arregloProducto)){ 
      Img::eliminar($arregloProducto['img'], 'productos/');
      return $mensaje;
    }

    //verificamos si es padre o hijo ---------------------------
    if($arregloProducto['es_padre'] == 1){
        // Actualizar producto
          if(Producto::actualizar($arregloProducto))

            //Actualizamos tallas
            foreach($producto['sizes'] as $size){ 
              //Arreglo para talla
                $arregloTallas = [
                  "nombre" => $size['name'],
                  "cantidad" => $size['quantity'],
                  "id" =>  $size['id'] ?? 0,
                  "codigo" =>  $arregloProducto['codigo'],
                  "id_producto" =>  $arregloProducto['id']
                ];
              //ejecutamos la actualizacion de la talla
              if ($arregloTallas['id'] > 0) {
                $resulTalla = Talla::actualizar($arregloTallas);
              }else{
                $resulTalla = Talla::crear($arregloTallas);
              }
                
              //Validamos la accion 
                if($resulTalla != true){ 
                  array_push($mensajesError, $resulTalla);
                }
            }   
          // Actualizamos la existencia del producto registrado
          if(!Producto::actualizarStock($producto['id'])) array_push($mensajesError,
            "{'mensaje': 'No se actualizo el Stock'}");  

      }else{ //NO ES PADRE -> ES HIJO 

          // Actualizar producto hijo
          $resulProductoHijo = Producto::actualizarProductoHijo($arregloProducto);
           if($resulProductoHijo != true){
              array_push($mensajesError, $resulProductoHijo);
           }

          //Actualizar Tallas 
          foreach ($producto['sizes'] as $size){
            $arregloTallas =
              [
                "codigo" => $arregloProducto['codigo'],
                "id" => $size['id'] ?? null,
                "nombre" => $size['name'],
                "cantidad" => $size['quantity'],
                "id_producto" => $arregloProducto['id']
              ];
             //ejecutamos la actualizacion de la talla
             if ($arregloTallas['id'] > 0) {
                $resulTalla = Talla::actualizar($arregloTallas);
              }else{
                $resulTalla = Talla::crear($arregloTallas);
              }
          }

          // Actualizamos la existencia del producto registrado
          if(!Producto::actualizarStock($producto['id'])) array_push($mensajesError,
            "{'mensaje': 'No se actualizo el Stock'}");  
      }
      
    // Validamos si la actualizacion se ejecuto sin errores
    if (count($mensajesError) > 0){
      //convertimos el array en cadena de texto
      $mensajesError = implode(', ', $mensajesError);
      return Validar::respuestasHttp($mensajesError . 'Fallo la acualización', 409);
    }else{
      return Validar::respuestasHttp('Actualización exitosa', 200);
    }

  }

  public function descuento(){
   //validamos el metodo de envio (se espera POST)
   if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], 
   "POST")) return $mensaje;

   // Datos recibidos
   $producto = Arreglar::paraJsonSingular($this->producto['producto']);

      $costoActual = Ayudador::datos($producto['id'], "productos", "id", "singular", "costo");
      //Validamos que el descuento no sea mayor al costo
      if($costoActual['costo'] <  $producto['descuento']){
        return Validar::respuestasHttp("El descuento no puede ser mayor al costo del producto", 409)  ;
      }else{
        if(!Producto::descuento($producto)){
          return Validar::respuestasHttp("No sufrio ningun cambio.", 200);
        }else{
          return Validar::respuestasHttp("Descuento actualizado.", 200);
        }
      }
  }



  
  // Eliminar producto
  /**
   * @param idProducto Puede recibir codigo o id para eliminar todo
   */
  public function eliminar($idProducto){
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], 
    "POST")) return $mensaje;

    // VALIDACION DE TODOS LOS DATOS RECIBIDOS 
    if($mensaje = Formulario::producto(["id" => $idProducto])) return $mensaje;

    //variables
    $heredo = false;
    //consultamos la informacion del producto PADRE o HIJO a eliminar
    $producto = Ayudador::datos($idProducto, "productos",
    "id", "singular", "codigo, img");
    
    #verificamos si es padre ---------------------------
    if(Producto::es_padre($idProducto)){
        //Llamamos a los hijos 
        $hijos = Producto::getHijos($producto['codigo']);
     
        //Heredamos al hijo mayor
        foreach ($hijos[0] as $hijo){
          if(!empty($hijo['id'])){
            if($hijo['id'] != $idProducto && $heredo == false){
              //otorgamos la primojenitura 
              $resulHeredero = Producto::heredero($hijo['codigo'], $producto['codigo']);
              if($resulHeredero != true) return Validar::respuestasHttp($resulHeredero . " No recibio la herencia", 409);
              $resulNuevoPadre = Producto::nuevoPadre($hijo['id']);
              if($resulNuevoPadre === true){
                $heredo = true;
              }else return Validar::respuestasHttp($resulHeredero . " No recibio el codigo padre", 409);
            }
          }  
        }

         //destruimos el registro
         $resulDestruccion = Ayudador::destruirRegistro($idProducto, $producto['img']);
         //validamos la destruccion  
         if($resulDestruccion != true){
           echo '{"mensaje": "Este producto no existe verifique su envio"}';
         }else{
          return Validar::respuestasHttp("elimino correctamente", 200);
         }
    }else{

      //ES HIJO SE ELIMINA Y LISTO

       //destruimos el registro
       $resulDestruccion = Ayudador::destruirRegistro($idProducto, $producto['img']);
       //validamos la destruccion  
       if($resulDestruccion != true){
         echo '{"mensaje": "Este producto no existe verifique su envio"}';
       }else{
         return Validar::respuestasHttp("elimino correctamente", 200);
       }
    }
       
  }


}
