<?php 
namespace App\Controllers;

use App\Models\{Favorito, Validar, Ayudador, Img, Formulario};

class FavoritoController
{
  private $favorito;
  //Recibimos los JSON
  public function __construct(){
    $this->favorito = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    json_decode($_POST['json'] ?? null, true) );
    // print_r($this->favorito);
  }

  public function listar($idUsuario){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las marcas
    if(!$favoritos = Favorito::listar($idUsuario)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($favoritos);
    }
  } 
  
  public function show($favorito){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la talla
    if(!$favorito = Favorito::show($favorito)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($favorito);
    }
  }

  public function crear(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

      //obtenemos los datos de la favorito y le quitamos los espacios del principio y final
      $favorito = Ayudador::quitarEspacios($this->favorito);

      //validamos que no tenga caracteres extraños
      if($mensaje = Formulario::favorito($favorito)) return $mensaje;
    //Cierre de validaciones

      //ejecutamos la creacion de la favorito
      $resultado = Favorito::crear($favorito);
      if($resultado === true){
          return Validar::respuestasHttp("Este producto (".$favorito['name'].") fue agregado a tu lista de favoritos con exito", 201);

      }else {
        return Validar::respuestasHttp($resultado, 409); 
      }
  } 
  
  // public function actualizar(){
    //   //Abrir validaciones  
    //     //validamos el metodo de envio (se espera POST)
    //     if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
      
    //     //obtenemos los datos de la favorito para editarla y limpiamos los espacios
    //     $favorito = Ayudador::quitarEspacios($this->favorito['favorito']); //datosenviados
      
    //     //validamos todos los campos 
    //     if($mensaje = Formulario::favorito($favorito)) return $mensaje;

    //     //Datos de la DB actuales
    //     $favoritoActual = favorito::show($favorito['id']);   
    //   //Cierre validaciones
    
    //   //ejecutamos la actualizacion 
    //   $resultado = Favorito::actualizar($favorito);
    //   if($resultado === true){
    //       return Validar::respuestasHttp("El producto favorito (". $favoritoActual['favorito']['nombre'] .") fue editada con exito", 200);
    //   }else {
    //     return Validar::respuestasHttp("El producto favorito (". $favoritoActual['favorito']['nombre'] .") no sufrio ningún cambio", 200); 
    //   }

  // }


  public function eliminar(){

    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
    //Recibimos los datos
    $favorito = $this->favorito;
    //validamos todos los campos 
    if($mensaje = Formulario::favorito($favorito)) return $mensaje;
    
    //Datos de la DB actuales  
    $favoritoActual = Favorito::show($favorito); 

    //Ejecutamos la accion de eliminar
    $favoritoEliminado = Favorito::eliminar($favorito);

    //Validamos el resultado de la acción 
    if($favoritoEliminado === true){
        return Validar::respuestasHttp("Elimino el producto de favorito, de Código: {$favoritoActual['id_producto']}, Nombre: ". $favoritoActual['nombre'],200);
    }else{
      return Validar::respuestasHttp($favorito, 409);
    }
  }



}//cierre de la clase

	
