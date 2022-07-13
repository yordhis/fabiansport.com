<?php 
namespace App\Controllers;

use App\Models\{Color, Validar, Ayudador, Formulario};

class ColorController
{
  private $color;
  //Recibimos los JSON
  public function __construct(){
    $this->color = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true) );
    // print_r($this->color);
  }

  public function listar(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las colors
    if(!$colores = Color::listar()){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($colores);
    }
  } 
  
  public function show($idColor){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la color
    if(!$color = Color::show($idColor)){
      return Validar::respuestasHttp("No hay resultados asd", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($color);
    }
  }

  public function crear(){
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
      
     //obtenemos los datos del color y aplicamos la funcion quitarEspacios
      //para editarla correctamente
      $color = Ayudador::quitarEspacios($this->color); 
      
      //validamos que no tenga caracteres extraños y mas
      if($mensaje = Formulario::color($color)) return $mensaje;

      //validamos que los caracteres no pasen de 50 lo permitido en la DB
      if($mensaje = Validar::longitudPermitida($color['nombre'], 150)) return $mensaje;
      
    //Cierre de validaciones

    //ejecutamos la creacion de la color
    $resultado = Color::crear($color);
    if($resultado === true){
      return Validar::respuestasHttp("El color (".$color['nombre'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
    //Abrir validaciones 
      //validamos el metodo de envio (se espera PUT)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos del color y aplicamos la funcion quitarEspacios
      //para editarla correctamente
        $color = Ayudador::quitarEspacios($this->color['color']); 
      //validamos que hallan datos
        if(!isset($color['id'])) return Validar::respuestasHttp("No hay identiicador para procesar la actualización", 409);
        
      //Validamos que la color que queremos editar si exista
        if($mensaje = Validar::siExisteElDato("Este id ". $color['id']  ." no esta asignado a ningun color", 
        $color['id'], "colores", "id")) return $mensaje;
      
      //Consultamos el color a actualizar 
        $colorActual = Color::show($color['id']); //Datos de la DB actuales
      
      //validamos que los caracteres no pasen de 50 lo permitido en la DB
        if($mensaje = Validar::longitudPermitida($color['nombre'] , 150)) return $mensaje;

      //validamos que no tenga caracteres extraños y mas
        if($mensaje = Formulario::color($color)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Color::actualizar($color);
    if($resultado === true){
      return Validar::respuestasHttp("El color (". $colorActual['color']['nombre'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("El color (". $colorActual['color']['nombre'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idColor){

  
    // print_r($colorActual); return;
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id del color no existe para ejecutar la acción", 
    $idColor, "colores", "id")) return $mensaje;

    $colorActual = Color::show($idColor); //Datos de la DB actuales

    //Ejecutamos la accion de eliminar
    $color = Color::eliminar($idColor);
    //Validamos el resultado de la acción 
    if($color === true){
      return Validar::respuestasHttp("Elimino el color de Código: {$colorActual['color']['id']}, Nombre: ". $colorActual['color']['nombre'],200);
    }else{
      return Validar::respuestasHttp($color, 409);
    }
  }



}//cierre de la clase

	
