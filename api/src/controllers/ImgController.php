<?php

namespace App\Controllers;

use App\Models\{Ayudador, Validar, Img, Producto, Marca, Factura};

class ImgController 
{
  private $img;
  //Recibimos los JSON
  public function __construct(){
    $this->img = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true) );
    //  print_r($this->img);
  }
  //eliminar imagen 
  public function eliminarImagen(){

    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
    // $datosImgDB = [];
    // var_dump(empty($datosImgDB)); return;
    $img = $this->img['imagen'];

    //obtenemos las imagenes de la base de datos
    $datosImgDB = Img::show($img['id'], $img['directorio']);
    if(empty($datosImgDB['imagenDB']['img'])){
      echo Validar::respuestasHttp("sin imagen", 200);
    }
  
    // print_r($datosImgDB);  return;  
    if(Img::eliminarImagen($img, $datosImgDB['imagenDB']['img'])){
      return Validar::respuestasHttp("La imagen (".$img['nombre'] .") se elimino correctamente", 200);
    }else{
      return Validar::respuestasHttp("La imagen (".$img['nombre'] .") No se elimino", 409);
    }
     
    
  }
}
