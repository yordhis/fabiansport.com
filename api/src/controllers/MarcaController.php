<?php 
namespace App\Controllers;

use App\Models\{Marca, Validar, Ayudador, Img, Formulario};

class MarcaController
{
  private $marca;
  //Recibimos los JSON
  public function __construct(){
    $this->marca = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true) );
    // print_r($this->marca);
  }

  public function listar(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las marcas
    if(!$marcas = Marca::listar()){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($marcas);
    }
  } 
  
  public function show($idMarca){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la talla
    if(!$marca = Marca::show($idMarca)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($marca);
    }
  }

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

      //obtenemos los datos de la marca y le quitamos los espacios del principio y final
      $marca = Ayudador::quitarEspacios($this->marca['marca']);

      //validamos que no tenga caracteres extraños
      if($mensaje = Formulario::marca($marca)) return $mensaje;
      
    //Cierre de validaciones
      //Obtenemos el nombre de la imagen y movemos las imagenes
      if($img = Img::mover($_FILES, $marca['directorio'] ?? null, $marca['nombre'])){
        $marca['img'] = $img;
      }
      //ejecutamos la creacion de la marca
      $resultado = marca::crear($marca);
      if($resultado === true){
          return Validar::respuestasHttp("La marca (".$marca['nombre'].") fue creada con exito", 201);

      }else {
        return Validar::respuestasHttp($resultado, 409); 
      }
  } 
  
  public function actualizar(){
    //Abrir validaciones  
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
     
      //obtenemos los datos de la marca para editarla y limpiamos los espacios
      $marca = Ayudador::quitarEspacios($this->marca['marca']); //datosenviados
    
      //validamos todos los campos 
      if($mensaje = Formulario::marca($marca)) return $mensaje;

      //Datos de la DB actuales
      $marcaActual = Marca::show($marca['id']);   
    //Cierre validaciones
   
    //Obtenemos el nombre de la imagen y revisamos si hay en la DB para añadir las
    if($img = Img::mover($_FILES, $marca['directorio'] ?? null, $marca['nombre'])){
      if(!empty($marcaActual['marca']['img'])){
        $marca['img'] = $marcaActual['marca']['img'] .",". $img;
      }else {
        $marca['img'] = $img;
      }
    }
    //ejecutamos la actualizacion 
    $resultado = Marca::actualizar($marca);
    if($resultado === true){
        return Validar::respuestasHttp("La marca (". $marcaActual['marca']['nombre'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La marca (". $marcaActual['marca']['nombre'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idMarca){

    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;
    
    //validamos todos los campos 
    if($mensaje = Formulario::marca(["id" => $idMarca])) return $mensaje;
    
    //Datos de la DB actuales  
    $marcaActual = Marca::show($idMarca); 

    //Ejecutamos la accion de eliminar
    $marca = Marca::eliminar($idMarca);

    //Validamos el resultado de la acción 
    if($marca === true){
      if(Img::eliminar($marcaActual['marca']['img'], 'marcas/') == true){
        return Validar::respuestasHttp("Elimino la marca de Código: {$marcaActual['marca']['id']}, Nombre: ". $marcaActual['marca']['nombre'],200);
      }
    }else{
      return Validar::respuestasHttp($marca, 409);
    }
  }



}//cierre de la clase

	
