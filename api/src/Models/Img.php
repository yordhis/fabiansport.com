<?php

namespace App\Models;

class Img
{

  //CONSULTA LAS IMAGENES DE UN ELEMENTO O ARTICULO
  static function show($id, $tabla){
    if($img = Ayudador::datos($id, $tabla,'id','singular', "img")){
      $respuestaLimpia['imagenDB'] = Arreglar::paraJsonSingular($img);
      return $respuestaLimpia;
    }else return false;
  }

  //CONSULTAMOS LAS IMAGENES A MODELAR DEL PRODUCTO PADRE
  static function imagenesModelo($codgioPadre){
    if($imagenesModelo = Ayudador::datos($codgioPadre,'productos',
    'mi_padre', 'plural','img, codigo', 'codigo ASC')){
    //  print_r($imagenesModelo[0]['codigo']);
      foreach ($imagenesModelo as $img) {
        $respuestaLimpia['codigo'][] = $img['codigo'];
        $respuestaLimpia['img'][] = Arreglar::imgUrl($img['img'], 'productos');
      }

      $v = count($respuestaLimpia['codigo']);
      $i = 0;
      foreach ($respuestaLimpia['img'] as $imgModelo) {
        $modelos[] = [$respuestaLimpia['codigo'][$i], reset($imgModelo)];
        $i++;
      }
     
      return $modelos;
    
      
    }else return "no hay modelos asignados";
  }
  //close


  static function mover($imagenes, $directorio = null, $codigoNombre, $imagenesActuales = null){
    //Directorio raiz url
    $url = "../img/";

    //Creamos el directorio nuevo /img/new_dir
    if (empty($directorio)) {
      echo Validar::respuestasHttp("La variable directorio no esta definida", 409);
      return;
    }
    if (!file_exists($url . $directorio)) {
      if (!mkdir($url . $directorio)) {
        echo Validar::respuestasHttp("No se pudo crear el directorio", 409);
        return false;
      }
    }

    //Configuramos los nombre de la imagen: codigoProdcuto_nombre
    $cadenaDeTextoDeImagenes = Img::normalizarImagen($codigoNombre, $imagenes);
    $arrayDeNombreDeImagenes = explode(",",  $cadenaDeTextoDeImagenes);
    // print_r($arrayDeNombreDeImagenes); return;
    //Movemos las imagens al directorio especiicado
    $i = 0;
    foreach ($imagenes['tmp_name'] as $tmpName) {
     
      if (!move_uploaded_file($tmpName, $url . $directorio . '/' . $arrayDeNombreDeImagenes[$i])) {
        echo Validar::respuestasHttp("La imagen no se pudo mover al directorio espesificado", 409);
        return false;
      }
      $i++;
    }
    
    if(empty($imagenesActuales)){
      return $cadenaDeTextoDeImagenes;
    }else{
      return $imagenesActuales.",".$cadenaDeTextoDeImagenes;
    }
  }

  //Generador de nombre de imagenes
  static function generarNombreImagen($nombre){
    if ($nombre == '') return false;
    else {
      $img = explode('.', $nombre);
      $img[0] = rand(1, 10000) . time();
      return $nuevoNombre = $img[0] . '.' . $img[1];
    }
  }


  //Normalizar imagenes para el registro o actualizacion 
  //RETORNAR LAS IMAGENSE: foto.jpg,foto1.jpg,...
  static function normalizarImagen($prefijo, $imagenes){
    $nombreDeLaImagen = '';

    // Normalizamos los nombres de las img
    foreach ($imagenes['name'] as $imgName) {
      $nombreDeLaImagen .=  $prefijo ."_". Img::generarNombreImagen($imgName) . ",";
    }
    return $nombreDeLaImagen = substr($nombreDeLaImagen, 0, -1);
  }

  //eliminar imagenes
  /**
   *  Esta funcion toma la cadena de texto de las imagenes 
   *  Las convierte en un array en caso de ser varias y las 
   *  elimina de una en una y crea una nueva cadena de texto
   *  con las imagenes que no se van a eliminar o si queda 
   *  vacio se inserta el texto @var Sin-imagen.  
   *  @param datosImg[id,nombres,directorio] Esto viene del formulario
   *  @param datosDB[img] Esto viene de la base de datos
   *  @param datosImg[directorio] este campo debe recibir la el nombre 
   *  de la tabla de la base de datos. 
   */
  static function eliminarImagen($datosImg, $imgDB){
    //variable donde se va a almacenar la actualizacion de las imagenes
    $nuevaCadenaDeImagenes = "";
    
    //hacemos el array de imagenes
    $imagenes = explode(",", $imgDB) ?? null;
    
    //reEscribimos la nueva cadena de texto de las imagenes
    foreach ($imagenes as $imagen){
      if ($datosImg['nombre'] != $imagen){
        $nuevaCadenaDeImagenes .= $imagen . ",";
      }
    }
    
    // limpiamos la coma
    $nuevaCadenaDeImagenes = substr($nuevaCadenaDeImagenes, 0, -1);

    //Actualizamos la imagen 
    $imgResul = Img::actualizar($datosImg['id'], 
                $nuevaCadenaDeImagenes ?? "Sin imagen", 
                $datosImg['directorio']);

    //Eliminamos la imagen de la carpeta
    if ($imgResul == true){
      $url = "../img/";
      $subDirectorio = $datosImg['directorio'] ."/" ?? null;
      $img = $datosImg['nombre'];
      $urlImg = $url . $subDirectorio . $img;
      //vemos si existe el archivo
        if(file_exists($urlImg)){
          unlink("$urlImg");
        }else{
          echo Validar::respuestasHttp("La imagen (" . $urlImg . ") no se encontro en el directorio",200);
        }
        return true;
      }else{
        return Validar::mensajeSql($imgResul);
      }
  }

  //
  static function eliminar($img, $directorio){
    //hacemos el array
    $imagenes = explode(",", $img);
    
    $errores = 0;
    $totalImagnesMover = count($imagenes);
    $imgNoEncontradas = "";
    //cuadro de accion
    foreach ($imagenes as $img){
      $url = "../img/";
      $subDirectorio = $directorio ?? null;
      $urlImg = $url . $subDirectorio . $img;

       if(file_exists($urlImg)){
          unlink($urlImg);
        }else{
          $imgNoEncontradas .= $urlImg . "-";
          $errores++;
        }
    }
      //validamos el proceso realizado
      if ($errores > 0){
        echo Validar::respuestasHttp("La imagen (" . $imgNoEncontradas . ") no se encontro en el directorio", 200);
        return true;
      }else{
        return true;
      }

  }

  static function actualizar($id, $imagenes, $tabla){
    include (APP_PATH . 'config/database.php');

    $sql = "UPDATE $tabla SET img = '" . $imagenes . "' WHERE id ='" . $id . "' ";
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute();
    if ($sentencia->rowCount()){
      return true;
    }else{
      return $sentencia->errorInfo();
    }
  }
}//cierre de clase marca
