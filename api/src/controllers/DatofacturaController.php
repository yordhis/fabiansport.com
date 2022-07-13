<?php 
namespace App\Controllers;

use App\Models\{Datofactura, Validar, Ayudador, formulario};

class DatofacturaController
{
  private $datoFactura = [];
  //Recibimos los JSON
  public function __construct(){
    $this->datoFactura = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  public function show($idDatoFactura){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    //validamos que recibimos un codigo
    if($mensaje = Formulario::infoFactura(["code" => $idDatoFactura])) return $mensaje;
    //Ejecutamos la solicitud de listar todas las tallas
    if(!$infoFactura = Datofactura::show($idDatoFactura)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($infoFactura);
    }
  } 

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        
      //obtenemos los datos de la infoFactura para editarla
      $datoFactura = Ayudador::quitarEspacios($this->datoFactura['invoice']); 
    
      //validamos 
      if($mensaje = Validar::noExisteElDato("Este código ".  $datoFactura['idUser']  ." ya existe ingrese otro", 
      $datoFactura['idUser'], "datos_factura", "id_usuario")) return $mensaje;

      if($mensaje = Formulario::infoFactura($datoFactura)) return $mensaje;
    //Cierre de validaciones
   
    //ejecutamos la creacion de la infoFactura
    $resultado = Datofactura::crear($datoFactura);
    if($resultado === true){
      return Validar::respuestasHttp("La información de Factura de (".$datoFactura['email'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
     
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la talla para editarla
      $datoFactura = Ayudador::quitarEspacios($this->datoFactura['invoice']); //datosenviados
     
      //validamos 
      if($mensaje = Validar::siExisteElDato("Este código ". $datoFactura['idUser']." no esta asignado a ninguna información de factura", 
      $datoFactura['idUser'] , "datos_factura", "id_usuario")) return $mensaje;

      if($mensaje = Formulario::infoFactura($datoFactura)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Datofactura::actualizar($datoFactura);
    if($resultado === true){
      return Validar::respuestasHttp("La información para Facturar de (". $datoFactura['email'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La información para Facturar de (". $datoFactura['email'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($idUser){
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la información de factura no existe para ejecutar la acción", 
    $idUser, "datos_factura", "id_usuario")) return $mensaje;
    //Datos de la DB actuales
    $datoFacturaActual = Datofactura::show($idUser); 

    //Ejecutamos la accion de eliminar
    $datoFactura = Datofactura::eliminar($idUser);
    //Validamos el resultado de la acción 
    if($datoFactura === true){
      return Validar::respuestasHttp("Elimino la información de facturación del usaurio: {$datoFacturaActual['datoFactura']['correo']}",200);
    }else{
      return Validar::respuestasHttp($datoFactura, 409);
    }
  }



}//cierre de la clase

	
