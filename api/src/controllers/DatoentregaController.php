<?php 
namespace App\Controllers;

use App\Models\{Datoentrega, Validar, Ayudador, formulario};

class DatoentregaController
{
  private $datoEntrega = [];
  //Recibimos los JSON
  public function __construct(){
    $this->datoEntrega = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  public function show($idUser){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    //validamos que recibimos un codigo
    if($mensaje = Formulario::infoFactura(["code" => $idUser])) return $mensaje;
    //Ejecutamos la solicitud de listar todas las tallas
    if(!$datoEntrega = Datoentrega::show($idUser)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($datoEntrega);
    }
  } 

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        
      //obtenemos los datos de la infoFactura para editarla
      $datoEntrega = Ayudador::quitarEspacios($this->datoEntrega['delivery']); 
    
      //validamos 
      if($mensaje = Validar::noExisteElDato("Este código ".  $datoEntrega['idUser']  ." ya existe ingrese otro", 
      $datoEntrega['idUser'], "datos_entrega", "id_usuario")) return $mensaje;

      if($mensaje = Formulario::infoFactura($datoEntrega)) return $mensaje;
    //Cierre de validaciones
   
    //ejecutamos la creacion de la infoFactura
    $resultado = Datoentrega::crear($datoEntrega);
    if($resultado === true){
      return Validar::respuestasHttp("La información de entrega de (".$datoEntrega['email'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
     
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la talla para editarla
      $datoEntrega = Ayudador::quitarEspacios($this->datoEntrega['delivery']); //datosenviados
     
      //validamos 
      if($mensaje = Validar::siExisteElDato("Este código ". $datoEntrega['idUser']." no esta asignado a ninguna información de factura", 
      $datoEntrega['idUser'] , "datos_entrega", "id_usuario")) return $mensaje;

      if($mensaje = Formulario::infoFactura($datoEntrega)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Datoentrega::actualizar($datoEntrega);
    if($resultado === true){
      return Validar::respuestasHttp("La información para Entrega de (". $datoEntrega['name'] .") fue actualizada con exito", 200);
    }else {
      return Validar::respuestasHttp("La información para Entrega de (". $datoEntrega['name'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idUser){
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la información de factura no existe para ejecutar la acción", 
    $idUser, "datos_entrega", "id_usuario")) return $mensaje;
    //Datos de la DB actuales
    $datoFacturaActual = Datoentrega::show($idUser); 

    //Ejecutamos la accion de eliminar
    $datoFactura = Datoentrega::eliminar($idUser);
    //Validamos el resultado de la acción 
    if($datoFactura === true){
      return Validar::respuestasHttp("Elimino la información de facturación del usaurio: {$datoFacturaActual['datoEntrega']['correo']}",200);
    }else{
      return Validar::respuestasHttp($datoFactura, 409);
    }
  }



}//cierre de la clase

	
