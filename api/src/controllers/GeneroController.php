<?php 
namespace App\Controllers;

use App\Models\{Genero, Validar, Ayudador, Formulario};

class GeneroController
{
  private $genero;
  //Recibimos los JSON
  public function __construct(){
    $this->genero = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    $_POST['json'] ?? null);
  }

  public function listar(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las generos
    if(!$generos = Genero::listar()){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($generos);
    }
  } 
  
  public function show($idGenero){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la genero
    if(!$genero = Genero::show($idGenero)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($genero);
    }
  }

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

      //obtenemos el nombre de la categoria para crear
      $genero = Ayudador::quitarEspacios($this->genero['genero']);

      //validamos todo los campos
      if($mensaje = Formulario::genero($genero)) return $mensaje;

    //Cierre de validaciones

    //ejecutamos la creacion de la genero
    $resultado = Genero::crear($genero);
    if($resultado === true){
      return Validar::respuestasHttp("El genero (".$genero['nombre'].") fue creado con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
    //Abrir validaciones 
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //Datos recibidos
      $genero = Ayudador::quitarEspacios($this->genero['genero']);

      //validamos todo los campos
      if($mensaje = Formulario::genero($genero)) return $mensaje;
      
      //Consultamos el genero a actualizar 
      $generoActual = Genero::show($genero['id']); //Datos de la DB actuales   
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Genero::actualizar($genero);
    if($resultado === true){
      return Validar::respuestasHttp("El genero (". $generoActual['genero']['nombre'] .") fue editado con exito", 200);
    }else {
      return Validar::respuestasHttp("El genero (". $generoActual['genero']['nombre'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idGenero){

    $generoActual = Genero::show($idGenero); //Datos de la DB actuales
    // print_r($generoActual); return;
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la genero no existe para ejecutar la acción", 
    $idGenero, "generos", "id")) return $mensaje;
    //Ejecutamos la accion de eliminar
    $genero = Genero::eliminar($idGenero);
    //Validamos el resultado de la acción 
    if($genero === true){
      return Validar::respuestasHttp("Elimino el genero de Código: {$generoActual['genero']['id']}, Nombre: ". $generoActual['genero']['nombre'],200);
    }else{
      return Validar::respuestasHttp($genero, 409);
    }
  }



}//cierre de la clase

	
