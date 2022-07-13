<?php 
namespace App\Controllers;

use App\Models\{Talla, Validar, Ayudador, formulario, Producto};

class TallaController
{
  private $talla;
  //Recibimos los JSON
  public function __construct(){
    $this->talla = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  public function listar($idProducto){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las tallas
    if(!$tallas = Talla::listar($idProducto)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($tallas);
    }
  } 
  
  public function show($idTalla){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la talla
    if(!$talla = Talla::show($idTalla)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($talla);
    }
  }

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        
      //obtenemos los datos de la talla para editarla
      $talla = Ayudador::quitarEspacios($this->talla['talla']); 
    
      //validamos que no tenga caracteres extraños
      if($mensaje = Formulario::talla($talla)) return $mensaje;

      //validamos que los caracteres no pasen de 50 lo permitido en la DB
      if($mensaje = Validar::longitudPermitida($talla['nombre'] , 11)) return $mensaje;
      if($mensaje = Validar::longitudPermitida($talla['codigo'] , 50)) return $mensaje;
      if($mensaje = Validar::longitudPermitida($talla['cantidad'] , 11)) return $mensaje;
      
    //Cierre de validaciones

    //ejecutamos la creacion de la talla
    $resultado = Talla::crear($talla);
    if($resultado === true){
      return Validar::respuestasHttp("La talla (".$talla['nombre'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){ 
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la talla para editarla
      $talla = Ayudador::quitarEspacios($this->talla['talla']); //datosenviados
      //validamos que hay id
      if(!isset($talla['id'])) return Validar::respuestasHttp("No hay identiicador para procesar la actualización", 409);
      //Datos de la DB actuales
      $tallaActual = Talla::show($talla['id']); 

      //Validamos que la talla que queremos editar si exista
      if($mensaje = Validar::siExisteElDato("Este id ". $talla['id']  ." no esta asignado a ninguna talla", 
      $talla['id'], "tallas", "id")) return $mensaje;
      
     
      //validamos que los caracteres no pasen de 50 lo permitido en la DB
      if($mensaje = Validar::longitudPermitida($talla['nombre'] , 11)) return $mensaje;
      if($mensaje = Validar::longitudPermitida($talla['codigo'] , 50)) return $mensaje;
      if($mensaje = Validar::longitudPermitida($talla['cantidad'] , 11)) return $mensaje;

      //validamos que no tenga caracteres extraños y mas
      if($mensaje = Formulario::talla($talla)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Talla::actualizar($talla);
    if($resultado === true){
      return Validar::respuestasHttp("La talla (". $tallaActual['talla']['nombre'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La talla (". $tallaActual['talla']['nombre'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idTalla){

    $tallaActual = Talla::show($idTalla); //Datos de la DB actuales
    
    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la talla no existe para ejecutar la acción", 
    $idTalla, "tallas", "id")) return $mensaje;
    //Ejecutamos la accion de eliminar
    $talla = Talla::eliminar($idTalla);
    //Validamos el resultado de la acción 
    if($talla === true){
      // Actualizamos la existencia del producto registrado
      if(!Producto::actualizarStock($tallaActual['talla']['id_producto'])) return "Fallo en actualizar stock"; 
      //mensaje
      return Validar::respuestasHttp("Elimino la talla de Código: {$tallaActual['talla']['codigo']}, Nombre: ". $tallaActual['talla']['nombre'],200);
       
    }else{
      return Validar::respuestasHttp($talla, 409);
    }
  }



}//cierre de la clase

	
