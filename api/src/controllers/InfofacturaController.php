<?php 
namespace App\Controllers;

use App\Models\{Infofactura, Validar, Ayudador, formulario};

class InfofacturaController
{
  private $infoFactura = [];
  //Recibimos los JSON
  public function __construct(){
    $this->infoFactura = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true));
  }

  public function show($codigoFactura){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    //validamos que recibimos un codigo
    if($mensaje = Formulario::infoFactura(["code" => $codigoFactura])) return $mensaje;
    //Ejecutamos la solicitud de listar todas las tallas
    if(!$infoFactura = Infofactura::show($codigoFactura)){
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
      $infoFactura = Ayudador::quitarEspacios($this->infoFactura); 
    
      //validamos 
      if($mensaje = Validar::noExisteElDato("Este código ".  $infoFactura['code']  ." ya existe ingrese otro", 
      $infoFactura['code'], "info_factura", "codigo")) return $mensaje;

      if($mensaje = Formulario::infoFactura($infoFactura)) return $mensaje;
    //Cierre de validaciones
   
    //ejecutamos la creacion de la infoFactura
    $resultado = Infofactura::crear($infoFactura, $infoFactura['code']);
    if($resultado === true){
      return Validar::respuestasHttp("La información de Factura codigo (".$infoFactura['code'].") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
     
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
      //obtenemos los datos de la talla para editarla
      $infoFactura = Ayudador::quitarEspacios($this->infoFactura['invoice']); //datosenviados
     
      //validamos 
      if($mensaje = Validar::siExisteElDato("Este código ". $infoFactura['code']." no esta asignado a ninguna información de factura", 
      $infoFactura['code'] , "info_factura", "codigo")) return $mensaje;

      if($mensaje = Formulario::infoFactura($infoFactura)) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Infofactura::actualizar($infoFactura);
    if($resultado === true){
      return Validar::respuestasHttp("La información Factura (". $infoFactura['code'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La información Factura (". $infoFactura['code'] .") no sufrio ningún cambio", 200); 
    }

  }


  public function eliminar($codigoFactura){

    $infoFacturaActual = Infofactura::show($codigoFactura); //Datos de la DB actuales
    // print_r($tallaActual); return;
    //validamos el metodo de envio (se espera DELETE)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la información de factura no existe para ejecutar la acción", 
    $codigoFactura, "info_factura", "codigo")) return $mensaje;
    //Ejecutamos la accion de eliminar
    $infoFactura = Infofactura::eliminar($codigoFactura);
    //Validamos el resultado de la acción 
    if($infoFactura === true){
      return Validar::respuestasHttp("Elimino la información factura de Código: {$infoFacturaActual['infoFactura']['codigo']}",200);
    }else{
      return Validar::respuestasHttp($infoFactura, 409);
    }
  }



}//cierre de la clase

	
