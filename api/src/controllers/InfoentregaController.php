<?php 
namespace App\Controllers;

use App\Models\{Infoentrega, Validar, Ayudador, formulario};

class InfoentregaController
{
  private $infoEntrega = [];
  //Recibimos los JSON
  public function __construct(){
    $this->infoEntrega = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  public function show($codigoFactura){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    //validamos que recibimos un codigo
    if($mensaje = Formulario::infoFactura(["code" => $codigoFactura])) return $mensaje;
    //Ejecutamos la solicitud de listar todas las tallas
    if(!$infoFactura = Infoentrega::show($codigoFactura)){
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
      $infoEntrega = Ayudador::quitarEspacios($this->infoEntrega);
      //validamos 
      if($mensaje = Validar::noExisteElDato("Este código ".  $infoEntrega['code']  ." ya existe ingrese otro", 
       $infoEntrega['code'], "info_entrega", "codigo")) return $mensaje;
    
      if($mensaje = Formulario::infoFactura($infoEntrega)) return $mensaje;
    //Cierre de validaciones
   
    //ejecutamos la creacion de la infoEntrega
    $resultado = infoentrega::crear($infoEntrega);
    if($resultado === true){
      return Validar::respuestasHttp("La información de Entrega de Factura codigo (".$infoEntrega['code'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
     
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la talla para editarla
      $infoEntrega = Ayudador::quitarEspacios($this->infoEntrega['delivery']); //datosenviados
   
      //validamos 
      if($mensaje = Validar::siExisteElDato("Este código ".$infoEntrega['code']." no esta asignado a ninguna información de entrega de factura", 
      $infoEntrega['code'] , "info_entrega", "codigo")) return $mensaje;

      if($mensaje = Formulario::infoFactura($infoEntrega)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Infoentrega::actualizar($infoEntrega);
    if($resultado === true){
      return Validar::respuestasHttp("La información de Entrega, codigo factura: (". $infoEntrega['code'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La información de Entrega, codigo factura: (". $infoEntrega['code'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($codigoFactura){

    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la información de entrega no existe para ejecutar la acción", 
    $codigoFactura, "info_entrega", "codigo")) return $mensaje;
    //Datos de la DB actuales
    $infoEntregaActual = Infoentrega::show($codigoFactura);
    //Ejecutamos la accion de eliminar
    $infoEntrega = Infoentrega::eliminar($codigoFactura);
    //Validamos el resultado de la acción 
    if($infoEntrega === true){
      return Validar::respuestasHttp("Elimino la información de entrega de la factura de Código: {$infoEntregaActual['infoEntrega']['codigo']}",200);
    }else{
      return Validar::respuestasHttp($infoEntrega, 409);
    }
  }



}//cierre de la clase

	
